# (c) 2020 Stefan Antonowicz
# Based off of tex found at https://github.com/ludus-leonis/nipajin
# This file is released under Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
# Please do not apply other licenses one-way.

VERSION=v.0.1

setup:
	mkdir -p out
	ln -sf ../../ygg.sty out/
	ln -sf ../../ocgx.sty out/
	ln -sf ../gods.tex out/
	ln -sf ../../thanks.tex out/

	xelatex -output-driver="xdvipdfmx -V 5 -z 9" -jobname="Signs_Small_Gods_${VERSION}" -output-directory out signs

clean:
	rm -rf out/*