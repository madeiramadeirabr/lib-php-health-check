#!/bin/bash

#sudo apt-get install jq

curl -s curl --location 'https://api.github.com/repos/madeiramadeirabr/mm-vulnerability-management-portal/commits/0aee812fb63285f8b634eea51bbea4cc549d8696/check-runs' \
--header 'Accept: application/vnd.github+json' \
--header 'Authorization: Bearer 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' \
--header 'X-GitHub-Api-Version: 2022-11-28' > response.json

conclusion=$(cat response.json | jq -r '.check_runs[] | select(.name == "SonarCloud Code Analysis").conclusion';)

echo "Conclusion: $conclusion"