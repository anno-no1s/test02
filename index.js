/**************************************************

	2018年5月テスト 問2プログラム

**************************************************/

const cheerio = require('cheerio');
const request = require('request');
const url = require('url');
const validUrl = require('valid-url');

const startUrl = 'https://no1s.biz/';
const regexp = new RegExp('^' + startUrl, 'i');
const resultUrls = [startUrl];

searchLink(startUrl);

function searchLink(searchUrl) {
  resultUrls.push(url.format(url.parse(searchUrl)));
  request(
    {method: 'GET', url: searchUrl, encoding: null},
    function (error, response, body) {
      if (!error && response.statusCode === 200) {
        const $ = cheerio.load(body, {
          decodeEntities: false
        });
        const title = $('head').find('title').text();
        console.log('%s   %s', searchUrl, title);

        // 指定のドメイン内のリンクのみ対象
        if (searchUrl.match(regexp)) {
          $('body').find('a').each(function(){
            const linkUrl = $(this).attr('href');
            if (validUrl.isUri(linkUrl) && resultUrls.indexOf(url.format(url.parse(linkUrl))) === -1) {
              searchLink(linkUrl);
            }
          });
        }
      }
    }
  );
}
