{
  pkgs ? import <nixpkgs> { }
  ,php81 ? import <phps>
  ,php85 ? import (fetchTarball "https://github.com/piotrkwiecinski/nixpkgs/archive/1c614d75004b9eb1ecda6ddeb959c4f544403de5.tar.gz") {}
  ,phpVersion ? "php81"
}:

let
  phpVersionPkgs =
    if (phpVersion == "php85") then php85.${phpVersion}
    else if (phpVersion == "php81") then php81.packages.x86_64-linux.${phpVersion}
    else pkgs.${phpVersion};
  php = phpVersionPkgs.buildEnv {
    extensions = { enabled, all }: enabled ++ (with all; [
      xdebug
    ]);

    extraConfig = ''
      xdebug.mode = debug
      memory_limit = 4G
    '';
  };
  inherit(phpVersionPkgs.packages) composer;

  projectInstall = pkgs.writeShellApplication {
    name = "project-install";
    runtimeInputs = [
      php
      composer
    ];
    text = ''
      rm -rf .Build/ vendor/ composer.lock
      composer update --prefer-dist --no-progress --working-dir="$PROJECT_ROOT"
    '';
  };

  projectCgl = pkgs.writeShellApplication {
    name = "project-cgl";

    runtimeInputs = [
      php
    ];

    text = ''
      ./vendor/bin/php-cs-fixer fix --config=Build/.php-cs-fixer.dist.php -v --dry-run --diff
    '';
  };

  projectCglFix = pkgs.writeShellApplication {
    name = "project-cgl-fix";

    runtimeInputs = [
      php
    ];

    text = ''
      ./vendor/bin/php-cs-fixer fix --config=Build/.php-cs-fixer.dist.php
    '';
  };

  projectLint = pkgs.writeShellApplication {
    name = "project-lint";

    runtimeInputs = [
      php
    ];

    text = ''
      find ./*.php Classes Configuration Tests -name '*.php' -print0 | xargs -0 -n 1 -P 4 php -l
    '';
  };

  projectPhpstan = pkgs.writeShellApplication {
    name = "project-phpstan";

    runtimeInputs = [
      php
    ];

    text = ''
      ./vendor/bin/phpstan analyse -c Build/phpstan.neon --memory-limit 256M
    '';
  };

  projectTestUnit = pkgs.writeShellApplication {
    name = "project-test-unit";
    runtimeInputs = [
      php
      projectInstall
    ];
    text = ''
      project-install
      ./vendor/bin/phpunit -c Build/UnitTests.xml
    '';
  };

  projectTestFunctional = pkgs.writeShellApplication {
    name = "project-test-functional";
    runtimeInputs = [
      php
      projectInstall
    ];
    text = ''
      project-install
      ./vendor/bin/phpunit -c Build/FunctionalTests.xml
    '';
  };

  projectTestAcceptance = pkgs.writeShellApplication {
    name = "project-test-acceptance";
    runtimeInputs = [
      projectInstall
      pkgs.sqlite
      pkgs.firefox
      pkgs.geckodriver
      pkgs.procps
      php
    ];
    text = ''
      project-install

      mkdir -p "$PROJECT_ROOT/.build/web/typo3temp/var/tests/acceptance"
      mkdir -p "$PROJECT_ROOT/.build/web/typo3temp/var/tests/acceptance-logs"
      mkdir -p "$PROJECT_ROOT/.build/web/typo3temp/var/tests/acceptance-reports"
      mkdir -p "$PROJECT_ROOT/.build/web/typo3temp/var/tests/acceptance-sqlite-dbs"

      export INSTANCE_PATH="$PROJECT_ROOT/.build/web/typo3temp/var/tests/acceptance"

      ./vendor/bin/codecept run

      pgrep -f "php -S" | xargs -r kill
      pgrep -f "geckodriver" | xargs -r kill
    '';
  };

in pkgs.mkShellNoCC {
  name = "TYPO3 Extension extcode/cart-products";
  buildInputs = [
    php
    composer
    projectInstall
    projectCgl
    projectCglFix
    projectLint
    projectPhpstan
    projectTestUnit
    projectTestFunctional
    projectTestAcceptance
  ];

  shellHook = ''
    export PROJECT_ROOT="$(pwd)"

    export typo3DatabaseDriver=pdo_sqlite
  '';
}
