# (c) 2020 Stefan Antonowicz
# Based off of tex found at https://github.com/ludus-leonis/nipajin
# This file is released under Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
# Please do not apply other licenses one-way.

PDF_NAME="BellBookCandle"
TEX_NAME="bbc"

setup:
	mkdir -p out
	ln -sf ../../ygg.sty out/
	ln -sf ../../ocgx.sty out/
	ln -sf ../mechanics.tex out/
	ln -sf ../leech.tex out/
	ln -sf ../witch.tex out/
	ln -sf ../sorcerer.tex out/
	ln -sf ../mystic.tex out/
	ln -sf ../spriggan.tex out/
	ln -sf ../appendices.tex out/
	ln -sf ../../thanks.tex out/

	xelatex -output-driver="xdvipdfmx -V 5 -z 9" -jobname=BellBookCandle -output-directory out bbc

clean:
	rm -rf out/*
