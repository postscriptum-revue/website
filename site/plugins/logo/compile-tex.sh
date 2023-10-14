#!/bin/bash

set -e

p_style=$1
s_style=$2
num=$3
page_dir=$4

for color in black white; do
	for version in ps psnum postscriptum; do
		# \newcommand is used to pass variables to the tex source file
		# at runtime.
		lualatex -jobname=logo-$num-$version-$color --output-directory=$page_dir "\newcommand{\pstyle}{$p_style}\newcommand{\sstyle}{$s_style}\newcommand{\logocolor}{$color}\newcommand{\version}{$version}\newcommand{\num}{$num}\input{logo.tex}"
	done
done

rm "$page_dir/"*.aux && rm "$page_dir/"*.log