apk add --no-cache git mysql-client zsh
git clone --depth=1 https://github.com/romkatv/powerlevel10k.git ~/powerlevel10k
echo "source ~/powerlevel10k/powerlevel10k.zsh-theme" >>~/.zshrc
cp ./.devcontainer/.p10k.zsh ~/.p10k.zsh
cp ./.devcontainer/.zshrc ~/.zshrc