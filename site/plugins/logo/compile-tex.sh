#!/bin/bash

set -e

p_style=$1
s_style=$2
page_dir=$3

for color in black white; do
	lualatex -jobname=logo-$color --output-directory=$page_dir "\newcommand{\pstyle}{$p_style}\newcommand{\sstyle}{$s_style}\newcommand{\logocolor}{black}\input{logo.tex}"
done

rm "$page_dir/"*.aux && rm "$page_dir/"*.log

# lualatex -jobname=logo-white --output-directory=logs "\newcommand{\pstyle}{$1}\newcommand{\sstyle}{$2}\newcommand{\logocolor}{white}\input{logo.tex}"

exit 0