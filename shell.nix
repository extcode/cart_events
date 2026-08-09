{
  pkgs ? import <nixpkgs> { }
  ,php81 ? import <phps>
  ,php85 ? import (fetchTarball "https://github.com/piotrkwiecinski/nixpkgs/archive/1c614d75004b9eb1ecda6ddeb959c4f544403de5.tar.gz") {}
  ,phpVersion ? "php82"
}:

let
  phpVersionPkgs =
    if (phpVersion == "php85") then php85.${phpVersion}
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
      composer
      php
    ];
    text = ''
      rm -rf .build/ composer.lock,
      composer update --prefer-dist --no-progress
    '';
  };

  projectCgl = pkgs.writeShellApplication {
    name = "project-cgl";

    runtimeInputs = [
      composer
      php
    ];

    text = ''
      composer project:cgl
    '';
  };

  projectCglFix = pkgs.writeShellApplication {
    name = "project-cgl-fix";

    runtimeInputs = [
      composer
      php
    ];

    text = ''
      composer project:cgl-fix
    '';
  };

  projectLintPhp = pkgs.writeShellApplication {
    name = "project-lint-php";

    runtimeInputs = [
      composer
      php
    ];

    text = ''
      composer project:lint:php
    '';
  };

  projectPhpstan = pkgs.writeShellApplication {
    name = "project-phpstan";

    runtimeInputs = [
      composer
      php
    ];

    text = ''
      composer project:phpstan
    '';
  };

  projectTestUnit = pkgs.writeShellApplication {
    name = "project-test-unit";
    runtimeInputs = [
      composer
      php
      projectInstall
    ];
    text = ''
      project-install
      composer project:test:unit
    '';
  };

  projectTestFunctional = pkgs.writeShellApplication {
    name = "project-test-functional";
    runtimeInputs = [
      composer
      php
      projectInstall
    ];
    text = ''
      project-install
      composer project:test:functional 
    '';
  };

  projectTestWithCoverage = pkgs.writeShellApplication {
    name = "project-test-with-coverage";
    runtimeInputs = [
      php
      projectInstall
    ];
    text = ''
      project-install
      XDEBUG_MODE=coverage .build/bin/phpunit -c Build/phpunit.xml.dist --coverage-html=coverage_result
    '';
  };

  projectTestAcceptance = pkgs.writeShellApplication {
    name = "project-test-acceptance";
    runtimeInputs = [
      pkgs.sqlite
      pkgs.firefox
      pkgs.geckodriver
      pkgs.procps
      php
      composer
    ];
    text = ''
      project-install

      mkdir -p "$PROJECT_ROOT/.build/public/typo3temp/var/tests/acceptance"
      mkdir -p "$PROJECT_ROOT/.build/public/typo3temp/var/tests/acceptance-logs"
      mkdir -p "$PROJECT_ROOT/.build/public/typo3temp/var/tests/acceptance-reports"
      mkdir -p "$PROJECT_ROOT/.build/public/typo3temp/var/tests/acceptance-sqlite-dbs"

      export INSTANCE_PATH="$PROJECT_ROOT/.build/public/typo3temp/var/tests/acceptance"

      .build/bin/codecept run

      pgrep -f "php -S" | xargs -r kill
      pgrep -f "geckodriver" | xargs -r kill
    '';
  };

in pkgs.mkShellNoCC {
  name = "TYPO3 Extension extcode/cart-products";
  buildInputs = [
    pkgs.cacert
    php
    composer
    projectInstall
    projectCgl
    projectCglFix
    projectLintPhp
    projectPhpstan
    projectTestUnit
    projectTestFunctional
    projectTestWithCoverage
    projectTestAcceptance
  ];

  shellHook = ''
    export PROJECT_ROOT="$(pwd)"

    export typo3DatabaseDriver=pdo_sqlite
  '';
}
