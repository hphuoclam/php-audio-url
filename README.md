## For `spotify` we need "Client ID", "Client Secret", "CallBack Url" for get all podcast. Please add all info same .env.example file
## Input: url
## Output: Array of podcast url, ['url', ...]

# Test cases:
+ https://tim.blog/podcast/ should return "Latest Episode mp4". => Work
+ https://tim.blog/2021/03/08/vitalik-buterin-naval-ravikant/ should return several possible episodes. => Work
+ https://www.dancarlin.com/product/hardcore-history-66-supernova-in-the-east-v/ => Work
+ https://podcasts.apple.com/us/podcast/the-tim-ferriss-show/id863897795
=> NOTE podcasts.apple.com are probably REALLY important to get correct, as a lot of people will be inputting that.
=> Work
+ https://open.spotify.com/show/4rOoJ6Egrf8K2IrywzwOMk 
=> NOTE well I did a brief glance and it looks pretty hard :) ouch. Figure it out!
=> Work (Need authencation from spotify)
+ https://www.youtube.com/watch?v=F16tyG_aqms => Work


# Install & Run Project:
+ Clone project:
> git clone https://github.com/hphuoclam/php-get-podcast-audio-url.git
+ Cd to project: 
> cd /php-get-podcast-audio-url
+ copy .env.example to .env: 
> sudo cp .env.example .env
+ Install composer: 
> composer install
+ package https://github.com/podcastcrawler/podcastcrawler required *php-tidy* : (https://stackoverflow.com/questions/58801692/how-to-install-enable-tidy-extension-in-php-7-2-on-ubuntu-14-04)
> sudo apt update
> sudo apt install php-tidy
+ start php serve with port 5000
> php -S localhst:5000

# Testing Video
> https://drive.google.com/file/d/1MneJKhsYtfW2ILzCXh8nPOotwy9-poZf/view?usp=sharing# php-audio-url
# php-audio-url
