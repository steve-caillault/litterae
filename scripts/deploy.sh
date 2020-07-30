#!/bin/bash

# Script de déploiement d'un site

branch=$1
environment=$2
site=$3

# Vérifit le paramètre de la branche
if [ -z "$1" ]
then
    echo "Paramètre de la branche manquant."
    exit
fi

# Vérifit le paramètre de l'environnement
if [ -z "$2" ]
then
    echo "Paramètre de l'environnement manquant."
    exit
else
    allowed_environments="testing,production"
    check_environment=`echo $allowed_environments | grep $environment`
    if [ -z $check_environment ] || [ $allowed_environments != $check_environment ]
    then
        echo "Environnement $environment inconnu."
        exit
    fi
fi

# Vérifit le paramètre du site
if [ -z "$3" ]
then
    echo "Paramètre du site manquant."
    exit
else
    allowed_sites="books,banks,nascar,steve-caillault"
    check_site=`echo $allowed_sites | grep $site`
    if [ -z $check_site ] || [ $allowed_sites != $check_site ]
    then
        echo "Site $site inconnu"
        exit
    fi
fi

home_directory=~
app_directory=$home_directory/www/$site/$environment
app_new_directory=$app_directory"_new"
app_old_directory=$app_directory"_old"
git_directory=$home_directory/deploy/git/$site
branch_directory="origin/$1"

# Sauvegarde du site actuel
zip -r $home_directory/saves/$site"_"$environment.zip $app_directory

# Récupération du dépôt
git -C $git_directory pull

# Vérifit que la branche existe dans le dépôt
exists=$(git -C $git_directory show-ref refs/remotes/$branch_directory)
if [ ! -n "$exists" ]
then
	echo "La branche $branch n'existe pas."
	exit
fi

# Suppression du répertoire temporaire, s'il existe, pour partir d'un répertoire vide
if [ -d $app_new_directory ]
then
	rm -rf $app_new_directory
fi
mkdir $app_new_directory

# Copie du contenu du dépôt vers le répertoire temporaire
git -C $git_directory archive --format zip --output $app_new_directory/git.zip $branch_directory
unzip -qq $app_new_directory/git.zip -d $app_new_directory
rm $app_new_directory/git.zip

# Suppresion des fichiers et répertoires inutiles
rm -rf $app_new_directory/database $app_new_directory/gulpfile.js $app_new_directory/package.json $app_new_directory/scripts

# Récupération des images existantes de l'ancien répertoire
if [ -d $app_directory/resources/images ]
then
    rm -rf $app_new_directory/resources/images
    mv $app_directory/resources/images $app_new_directory/resources
fi

# Remplace la valeur de l'environement dans le fichier .htaccess
const_environment=`echo $environment | tr '[:lower:]' '[:upper:]'`
sed -i -e "s/DEVELOPMENT/$const_environment/g" $app_new_directory/.htaccess

# Cré le fichier .environment
cd $app_new_directory
php cli environment $environment

# Optimisation des fichiers CSS et JavaScript
php cli static-files-version

# Supprime tous les fichiers .gitignore
find $app_new_directory/.gitignore -name ".gitignore" -type f -exec rm -f {} \;

# Utilise le nouveau répertoire et supprime l'ancien
mv $app_directory $app_old_directory
mv $app_new_directory $app_directory
rm -rf $app_old_directory

echo "Déploiement terminé."