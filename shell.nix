{
  pkgs ? import <nixpkgs> { }
  ,phpVersion ? "php81"
}:

let
  php = pkgs.${phpVersion}.buildEnv {
    extensions = { enabled, all }: enabled ++ (with all; [
      xdebug
    ]);

    extraConfig = ''
      xdebug.mode = debug
      memory_limit = 4G
    '';
  };
  inherit(pkgs."${phpVersion}Packages") composer;

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

  projectPhpstan = pkgs.writeShellApplication {
    name = "project-phpstan";

    runtimeInputs = [
      php
    ];

    text = ''
      ./vendor/bin/phpstan analyse -c Build/phpstan.neon --memory-limit 256M
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

in pkgs.mkShellNoCC {
  name = "TYPO3 Extension extcode/cart-products";
  buildInputs = [
    php
    composer
    projectInstall
    projectPhpstan
    projectCgl
    projectCglFix
    projectTestUnit
  ];

  shellHook = ''
    export PROJECT_ROOT="$(pwd)"

    export typo3DatabaseDriver=pdo_sqlite
  '';
}
