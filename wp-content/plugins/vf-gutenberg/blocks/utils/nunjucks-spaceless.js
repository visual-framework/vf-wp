/**
 * Nunjucks {% spaceless %} extension
 * from `vf-core`
 */

function SpacelessExtension() {
  this.tags = ['spaceless'];

  this.parse = function(parser, nodes, lexer) {
    var tok = parser.nextToken();

    var args = parser.parseSignature(null, true);
    parser.advanceAfterBlockEnd(tok.value);

    var body = parser.parseUntilBlocks('error', 'endspaceless');
    var errorBody = null;
    if (parser.skipSymbol('error')) {
      parser.skip(lexer.TOKEN_BLOCK_END);
      errorBody = parser.parseUntilBlocks('endremote');
    }

    parser.advanceAfterBlockEnd();

    return new nodes.CallExtension(this, 'run', args, [body, errorBody]);
  };

  this.run = function(context, body) {
    return body()
      .replace(/\s+/g, ' ')
      .replace(/>\s</g, '><');
  };
}

export default SpacelessExtension;
