### Copyright 1999-2017. Parallels IP Holdings GmbH. All Rights Reserved.

server ENV['HOST'], :web
set :user, 'root'
set :deploy_prefix, '/usr/local/psa'
set :module_name, File.basename(Dir.pwd)

desc 'Deploy source code to working machine.'
task :deploy do
  find_servers.each do |server|
    cmd = "rsync -avzr --no-owner --no-group --delete --exclude .git"
    run_locally "#{cmd} htdocs/* #{user}@#{server}:#{deploy_prefix}/admin/htdocs/modules/#{module_name}/"
    run_locally "#{cmd} plib/* meta.xml #{user}@#{server}:#{deploy_prefix}/admin/plib/modules/#{module_name}/"
  end
end

def run_locally(cmd)
  logger.trace "executing locally: #{cmd.inspect}" if logger
  system(cmd)
end
