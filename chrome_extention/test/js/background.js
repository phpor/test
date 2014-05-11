/**
 * Created by junjie on 14-5-11.
 */
console.log('I am in background.js');
console.log(chrome.webRequest);
chrome.webRequest.onBeforeSendHeaders.addListener(function(req) {
    console.log('onBeforeSendHeaders');
    console.log(req);
}, {urls: ["<all_urls>"]}, ['requestHeaders', 'blocking']);

chrome.commands.onCommand.addListener(function(command)
{
    if ('test' == command) {
        console.log('I received a command');
    }
});
