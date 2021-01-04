# (c) 2020 Stefan Antonowicz
# Based off of tex found at https://github.com/ludus-leonis/nipajin
# This file is released under Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
# Please do not apply other licenses one-way.

# You must use tabs, not spaces!

VERSION=v.1.0

setup:
	mkdir -p out
	mkdir -p out/tropes
	mkdir -p out/species
	mkdir -p out/arcana
	mkdir -p out/research
	mkdir -p out/marvels
	mkdir -p out/wonders
	mkdir -p out/appendices
	ln -sf ../ygg.sty out/
	ln -sf ../ocgx.sty out/
	ln -sf ../introduction.tex out/
	ln -sf ../mechanics.tex out/
	ln -sf ../theband.tex out/
	#Tropes
	ln -sf ../tropes.tex out/
	ln -sf ../../tropes/sellswords.tex out/tropes/
	ln -sf ../../tropes/knaves.tex out/tropes
	ln -sf ../../tropes/philosophers.tex out/tropes
	ln -sf ../../tropes/mystics.tex out/tropes
	#Species
	ln -sf ../species.tex out/
	ln -sf ../../species/pooka.tex out/species/
	ln -sf ../../species/spriggan.tex out/species/
	ln -sf ../../species/night_children.tex out/species/
	ln -sf ../../species/murks.tex out/species/
	ln -sf ../advancement.tex out/
	ln -sf ../skills_and_saves.tex out/
	ln -sf ../civilization.tex out/
	ln -sf ../miscellania.tex out/
	ln -sf ../arbiter.tex out/
	#Arcana
	ln -sf ../arcana.tex out/
	ln -sf ../../arcana/charms.tex out/arcana/
	ln -sf ../../arcana/leechcraft.tex out/arcana/
	ln -sf ../../arcana/liturgies.tex out/arcana/
	ln -sf ../../arcana/mysteries.tex out/arcana/
	ln -sf ../../arcana/necromancy.tex out/arcana/
	ln -sf ../../arcana/sacraments.tex out/arcana/
	ln -sf ../../arcana/whispers.tex out/arcana/
	ln -sf ../../arcana/wizardry.tex out/arcana/
	#Research
	ln -sf ../research.tex out/
	ln -sf ../../research/chymistry.tex out/research/
	ln -sf ../../research/inscription.tex out/research/
	ln -sf ../../research/medicinals.tex out/research/
	#Marvels
	ln -sf ../marvels.tex out/ 
	ln -sf ../../marvels/miracles.tex out/marvels/
	ln -sf ../../marvels/occultism.tex out/marvels/
	#Wonders
	ln -sf ../wonders.tex out/ 
	ln -sf ../../wonders/staff.tex out/wonders/
	ln -sf ../../wonders/sword out/wonders/
	#Appendices
	ln -sf ../appendices.tex out/
	ln -sf ../../appendices/appendix-a.tex out/appendices/
	ln -sf ../../appendices/appendix-b.tex out/appendices/
	ln -sf ../../appendices/appendix-c.tex out/appendices/
	ln -sf ../../appendices/appendix-d.tex out/appendices/
	ln -sf ../../appendices/appendix-e.tex out/appendices/	
	ln -sf ../../appendices/appendix-n.tex out/appendices/
	xelatex -output-driver="xdvipdfmx -V 5 -z 9" -jobname="Totality_of_Ygg_${VERSION}" -output-directory out rulebook

clean:
	rm -rf out/*
