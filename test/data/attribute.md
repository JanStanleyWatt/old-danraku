AttributeExtensionテスト
{.danraku}

[AttributeExtensionについて](https://commonmark.thephpleague.com/2.0/extensions/attributes/)
{#what-is-attr}

>普通の段落にも適応されるのだから引用にも当然適応される
{: title="aikotoba"}

`インラインコードブロックにも適応される`{contenteditable="false"}

*イタリックにも当然*{:.underline}

**強調も言わずもかな**{style="color: yellow"}

***イタリック＆強調はイタリックか強調かのいずれかだけ*{style="color: yellow"}**

***さもないと、下の記述のように・・・**{:.underline}*

***イタリックが二つ定義されるような不思議な挙動を起こす*{:.underline}*{style="color: yellow"}*

~~使う人がいるかどうかはさておき~~{:datatime="2021-09-17"}打消線にも適応される