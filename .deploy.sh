shopt -s expand_aliases
source "/mnt/d/remi/Projets web/remiscanfr/.deploy.base.sh"

from="."
to="$basepath_vps/www/colori/lib"

deploy "$from" "$to"