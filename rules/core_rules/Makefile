# (c) 2009-2015 by Markus Leupold-Löwenthal
# This file is released under CC BY-SA 4.0. Please do not apply other licenses one-way.
# This file is also released under CC BY 4.0.

PDF_NAME="TotalityOfYgg"
TEX_NAME="core_rules"


setup:
	mkdir -p out
	ln -sf ../../ygg.sty out/
	ln -sf ../../ocgx.sty out/
	ln -sf ../mechanics.tex out/
	ln -sf ../allies.tex out/
	xelatex -output-driver="xdvipdfmx -V 5 -z 9" -jobname=${PDF_NAME} -output-directory out ${TEX_NAME}

clean:
	rm -rf out/*
