/**
 * Created by junjie on 14-5-11.
 */

console.log("I am in content.js of phpor extension");
if (/baidu\.com/.test(window.location.href)) {
   // console.log('I am not wrong');
    document.body.innerHTML = '<h2>Baidu was hacked By Phpor</h2>';
}
