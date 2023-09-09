echo ""; echo "# Installing HostMeow... please wait"

cp sample.configuration.php configuration.php && echo "- Configuration script created"
cp script/test/sample.http.csv script/test/http.csv && echo "- Host list file created"
echo ""

read -p "Modify configuration file? [yn] " UserConsent
if [[ $UserConsent = y ]] ; then
  nano configuration.php
fi

read -p "Modify host list? [yn] " UserConsent
if [[ $UserConsent = y ]] ; then
  nano script/test/http.csv
fi

echo ""; echo "# HostMeow installation complete"; echo ""
