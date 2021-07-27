#!/usr/bin/env bash
DRUPAL="$(pwd)"
SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"

#git 忽略提交某个指定的文件(不从版本库中删除)
#git update-index --assume-unchanged sites/default/settings.php
# 确认数据库用户名和密码(当前是root,root)
vendor/bin/drush site:install standard -y --site-name="艾瑞seo" --account-pass=admin --db-url=mysql://root:@localhost:3306/airui
# Enable modules
vendor/bin/drush en -y \
  config_translation \
  drush_language \
  translation

# 文本编辑时，保存远程图片；需要在文本格式和编辑器的Basic html里面勾选
#image Resize Filter: Link images derivates to source Link an image derivate to its source (original) image.
#Image Resize Filter: Resize images based on their given height and width attributes
vendor/bin/drush en -y image_resize_filter

# common modules
vendor/bin/drush en -y entity_plus \
  field_group \
  views_plus \
  views_template \
  xmlsitemap \
  metatag \
  pathauto \
  image_resize_filter

#  为了添加option_button_with_none_label插件
vendor/bin/drush en -y eabax_core

#提前启用,以便于其他模块给seo角色赋予权限
vendor/bin/drush en -y seoer

vendor/bin/drush en -y \
  dsi_login \
  seo_station_model \
  seo_station_model_url \
  seo_station \
  seo_station_tkdb \
  seo_textdata \
  seo_site \
  seo_negotiator \
  seo_station_address \
  seo_logo \
  seo_flink \
  spiders

vendor/bin/drush en -y seo_front
vendor/bin/drush en -y dsi_block
vendor/bin/drush pmu -y toolbar \
  update

# 以下部分，始终放到最后处理.
#==============================
# Install zh-hans language
vendor/bin/drush language:add zh-hans

vendor/bin/drush language:default zh-hans

vendor/bin/drush cset -y language.negotiation url.prefixes.en "en"
vendor/bin/drush cset -y language.types negotiation.language_interface.enabled.language-browser 0

vendor/bin/drush then -y xbarrio
vendor/bin/drush cset system.theme default xbarrio -y
vendor/bin/drush cset system.theme admin xbarrio -y

vendor/bin/drush langimp --langcode=zh-hans modules/dsi/refactor/undef/contrib/translation/translations/drupal.zh-hans.po

vendor/bin/drush upwd admin admin

vendor/bin/drush en -y memcache
echo "include \$app_root . '/' . \$site_path . '/settings.memcache.php';" >> sites/default/settings.php
