SRC = ["prg-bib.php"]
ZIP = "prg-bib.zip"

file ZIP => SRC do
  dirname = File.basename(Dir.pwd)
  sh "cd ..; zip #{dirname}/#{ZIP} #{SRC.map{ |s| dirname+"/"+s}.join(" ")}"
end

task :clean do
  rm ZIP
end

task default: [ZIP]
