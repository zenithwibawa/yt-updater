# Youtube API: Video's Title Updater
## Required
- Install Google API Client `composer require google/apiclient:^2.0`
- Create file **api.json**
```
{
    "video_key":"YOUR_VIDEO_ID",
    "api_key":"API_KEY",
    "refresh_token":"REFRESH_TOKEN"
}
```
- Create file **client_secret.json** (it's from Google Developer's Console)
```
{"web":{"client_id":"","project_id":"","auth_uri":"https://accounts.google.com/o/oauth2/auth","token_uri":"https://oauth2.googleapis.com/token","auth_provider_x509_cert_url":"https://www.googleapis.com/oauth2/v1/certs","client_secret":"","redirect_uris":[""]}}
```
