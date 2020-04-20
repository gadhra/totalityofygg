# (c) 2020 Stefan Antonowicz
# Based off of tex found at https://github.com/ludus-leonis/nipajin
# This file is released under Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
# Please do not apply other licenses one-way.

PDF_NAME="TotalityOfYgg"
TEX_NAME="core_rules"

setup:
	mkdir -p out
	ln -sf ../../ygg.sty out/
	ln -sf ../../ocgx.sty out/
	ln -sf ../mechanics.tex out/
	ln -sf ../mortals.tex out/
	ln -sf ../skills_and_saves.tex out/
	ln -sf ../fae.tex out/

	xelatex -output-driver="xdvipdfmx -V 5 -z 9" -jobname=TotalityofYgg -output-directory out core_rules

clean:
	rm -rf out/*
