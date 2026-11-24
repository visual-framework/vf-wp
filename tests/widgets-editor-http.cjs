// Local admin request with a temporary session; does not change widget content.
const {execFileSync} = require('node:child_process');
const http = require('node:http');
const assert = require('node:assert/strict');
const wp = code => execFileSync('docker',['exec','-w','/var/www/html/dist','emblebivfwp_php','wp','eval',code],{encoding:'utf8'}).trim();
(async () => {
  const session = JSON.parse(wp('$users=get_users(array("role"=>"administrator","number"=>1)); $id=$users[0]->ID; $expiry=time()+300; $token=WP_Session_Tokens::get_instance($id)->create($expiry); echo wp_json_encode(array("id"=>$id,"token"=>$token,"url"=>admin_url("widgets.php"),"cookies"=>array(AUTH_COOKIE=>wp_generate_auth_cookie($id,$expiry,"auth",$token),LOGGED_IN_COOKIE=>wp_generate_auth_cookie($id,$expiry,"logged_in",$token))));'));
  try {
    const url = new URL(session.url);
    const result = await new Promise((resolve,reject) => {
      const req = http.get({hostname:'127.0.0.1',port:url.port,path:url.pathname,headers:{Host:url.host,Cookie:Object.entries(session.cookies).map(([k,v])=>k+'='+v).join('; ')}},res => {
        let body=''; res.setEncoding('utf8'); res.on('data',data=>body+=data); res.on('end',()=>resolve({status:res.statusCode,body}));
      });
      req.setTimeout(30000,()=>req.destroy(new Error('Widgets request timed out'))); req.on('error',reject);
    });
    assert.equal(result.status,200);
    assert.match(result.body,/id=['"]wp-edit-widgets-js['"]/);
    assert.doesNotMatch(result.body,/id=['"]wp-editor-js['"]/);
    assert.doesNotMatch(result.body,/script should not be enqueued together with the new widgets editor/);
    console.log('PASS: Widgets admin page loads the block widgets editor without wp-editor or the reported notice.');
    console.log('PASS: actual Widgets admin page loads wp-edit-widgets, omits wp-editor, and has no editor conflict notice.');
  } finally {
    wp('WP_Session_Tokens::get_instance('+Number(session.id)+')->destroy(base64_decode("'+Buffer.from(session.token).toString('base64')+'"));');
  }
})().catch(error=>{console.error(error);process.exitCode=1;});
