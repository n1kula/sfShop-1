set :user,          "Użytkownik dla SSH"

ssh_options[:port] = "Port SSH"

set :use_sudo,      false
set :clear_controllers, false

set :domain,        "Domena pod którą będzie dostępna nasza aplikacja"
set :deploy_to,     "Katalog na serwerze gdzie będzie nasza aplikacja"

set :branch,            fetch(:branch, "Wskazujemy brancha z jakiego chcemy skorzytać, np. develop, master") #cap -S branch=develop
set :symfony_env_prod,  fetch(:env, "prod") #cap -S env=prod

set :composer_options,  "--verbose --no-dev --prefer-dist --optimize-autoloader --no-progress" #add --no-dev for prod - dla composera

role :web,           "Adres serwera"                         # Your HTTP server, Apache/etc
role :app,           "Adres serwera", :primary => true       # This may be the same as your `Web` server

set :webserver_user,      "apache"

task :upload_parameters do
    origin_file = "app/config/deploy/production_parameters.yml"
    destination_file = shared_path + "/app/config/parameters.yml"

    try_sudo "mkdir -p #{File.dirname(destination_file)}"
    top.upload(origin_file, destination_file)
end