README = "readme.txt"
PHP = "prg-bib.php"
SRC = [PHP, "resize-iframe.js", README]
GIT_TAG = `git describe --tag --exact-match`.chomp
README_TAG = /^Stable tag: *(.*) *$/.match(open(README).read)[1]
PHP_TAG = /\/*.*Version: *([^ ]*) *$/m.match(open(PHP).read)[1]
ZIP = "prg-bib-#{README_TAG}.zip"
raise("tag mismatch: #{GIT_TAG}(git), #{README_TAG}(#{README}), "+
      "#{PHP_TAG}(#{PHP})") \
      unless GIT_TAG == README_TAG && README_TAG == "v"+PHP_TAG
changes=`git status --porcelain --untracked-files=no`.chomp
raise "there are changes: #{changes}" unless changes.empty?

file ZIP => SRC do
  dirname = File.basename(Dir.pwd)
  sh "cd ..; zip #{dirname}/#{ZIP} #{SRC.map{ |s| dirname+"/"+s}.join(" ")}"
end

task :clean do
  rm ZIP
end

task default: [ZIP]
