# 30 second websites
Archive of experimental websites made by students of Klasse Digitale Grafik at HFBK Hamburg.

## Submit your website

1. Request a SFTP account to connect to our shared server
2. Go to the `content` directory
3. Create a new folder like `YYYY-your-project-name`. Please note that you can only use `a-z0-9` and `-` characters

### Project metadata

4. Create a new file `website.json`

You can provide some metadata about your project using the `website.json` file like:
```json
{
  "title": "Project title /* string(60) */",
  "description": "Short description of your project /* string(200) */",
  "credits": "Your name, people or things you want to credit /* string(200) */",
  "url": "https://... url to external project website /* string(200) */",
}
```

Be careful to provide valid json and not accidentally add a comma after the last entry. All string will be shortened to the length stated above.

### For small static or php websites

Your project can be hosted on our server.

5. Upload your website into the directory you just created. `index.html` or `index.php` will be directory index, just like with any other apache server.

### For larger websites or non-Apache hosting

If you need databases, backend, have a lot of heavy assets, please host your project yourself.

5. Use the `website.json` file to provide a `source` url, from where the website can be embedded. The `url` field will be ignored, like:
```json
{
  "source": "https://... url if you want to /* string(200) */"
}
```

### Troubleshooting

- Does your website work properly when you enter the website url directly?
- Does your website allow iframe embeds?
- Do you have `.htaccess` rules (e.g. rewrite) that conflicts with ours?

For external websites:
- Please make sure your host allows our host for Cross-Origin-Resource-Sharing (CORS)

## Development

Run local PHP server
```
php -S localhost:8000
```
