遍历文件
for /R . %f in (*) do @echo %f

遍历目录：
for /R . %f in (.) do @echo %f
