import requests


response = requests.get('https://api.github.com/repos/madeiramadeirabr/mm-vulnerability-management-portal/commits/0aee812fb63285f8b634eea51bbea4cc549d8696/check-runs', 
                        headers={
                            'Authorization': 'Bearer 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'
                        }
                        )