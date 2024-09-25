README = "readme.txt"
SRC = ["prg-bib.php"]
GIT_TAG = `git describe --tag --exact-match`.chomp
README_TAG = /^Stable tag: *(.*) *$/.match(open(README).read)[1]
ZIP = "prg-bib-#{README_TAG}.zip"
raise "tag mismatch: #{GIT_TAG}(git), #{README_TAG}(#{README})" \
      unless GIT_TAG == README_TAG

file ZIP => SRC do
  dirname = File.basename(Dir.pwd)
  sh "cd ..; zip #{dirname}/#{ZIP} #{SRC.map{ |s| dirname+"/"+s}.join(" ")}"
end

task :clean do
  rm ZIP
end

task default: [ZIP]
