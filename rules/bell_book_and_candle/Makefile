# (c) 2020 Stefan Antonowicz
# Based off of tex found at https://github.com/ludus-leonis/nipajin
# This file is released under Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
# Please do not apply other licenses one-way.

# You must use tabs, not spaces!

VERSION=v.3.0.beta

setup:
	mkdir -p out
	ln -sf ../../ygg.sty out/
	ln -sf ../../ocgx.sty out/
	ln -sf ../mechanics.tex out/
	ln -sf ../philosophers.tex out/
	ln -sf ../mystics.tex out/
	ln -sf ../arcana.tex out/
	ln -sf ../research.tex out/
	ln -sf ../marvels.tex out/ 
	ln -sf ../appendices.tex out/
	ln -sf ../../thanks.tex out/
	xelatex -output-driver="xdvipdfmx -V 5 -z 9" -jobname="Bell_Book_and_Candle_${VERSION}" -output-directory out bbc

clean:
	rm -rf out/*
