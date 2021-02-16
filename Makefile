CFLAGS = -Ilibraries/libxl/include_c -Llibraries/libxl/lib -lxl -l mysqlclient

program: libxl.dylib
	gcc main.c -o main $(CFLAGS)
#	cp main /Applications/MAMP/htdocs/QuickBaluchon/main

libxl.dylib:
	cp ../libraries/libxl/lib/libxl.dylib ./
