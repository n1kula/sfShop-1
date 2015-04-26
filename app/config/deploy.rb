set :stages,        %w(production staging)
set :default_stage, "staging"
set :stage_dir,     "app/config/deploy"

require 'capistrano/ext/multistage'

ssh_options[:forward_agent] = true

set :application,   "Tutaj nazwa aplikacji"

set :app_path,      "app"
set :web_path,      "web"

set :repository,    "Tutaj wpisujemy adres naszego repozytorium"
set :scm,           :git

set :model_manager, "doctrine"
set :keep_releases,  3

set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,   [app_path + "/logs", "vendor"] 

set :writable_dirs,       ["app/cache", "app/logs"]
set :permission_method,   :acl
set :use_set_permissions, true

default_run_options[:pty] = true

set :use_composer,      true
set :copy_vendors,      true
set :dump_assetic_assets,   true
set :interactive_mode,      true

namespace :deploy do
    task :update_acl, :roles => :app do
        shared_dirs = [
            app_path + "/logs",
            app_path + "/cache"
        ]

    pretty_print "--> Setting up setfacl for shared directories"
    # Allow directories to be writable by webserver and this user
    run "cd #{latest_release} && setfacl -R -m u:#{webserver_user}:rwx -m u:#{user}:rwx #{shared_dirs.join(' ')}"
    run "cd #{latest_release} && setfacl -dR -m u:{#webserver_user}:rwx -m u:#{user}:rwx #{shared_dirs.join(' ')}"
    puts_ok
  end
end

logger.level = Logger::MAX_LEVEL

after "deploy:setup", "upload_parameters"
after "deploy", "deploy:cleanup"
