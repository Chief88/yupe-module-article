<?php

class m000000_000000_article_base extends yupe\components\DbMigration
{

    public function safeUp()
    {
        $this->createTable(
            '{{article_article}}',
            [
                'id'                => 'pk',
                'category_id'       => 'integer DEFAULT NULL',
                'lang'              => 'char(2) DEFAULT NULL',
                'creation_date'     => 'datetime NOT NULL',
                'change_date'       => 'datetime NOT NULL',
                'date'              => 'date NOT NULL',
                'title'             => 'varchar(250) NOT NULL',
                'alias'             => 'varchar(150) NOT NULL',
                'name_author'       => 'varchar(150) NOT NULL',
                'short_text'        => 'text',
                'full_text'         => 'text NOT NULL',
                'image'             => 'varchar(300) DEFAULT NULL',
                'video_url'         => 'varchar(300) DEFAULT NULL',
                'link'              => 'varchar(300) DEFAULT NULL',
                'user_id'           => 'integer DEFAULT NULL',
                'status'            => 'integer NOT NULL DEFAULT "0"',
                'is_protected'      => 'boolean NOT NULL DEFAULT "0"',
                'seo_keywords'      => 'varchar(250) NOT NULL',
                'seo_description'   => 'varchar(250) NOT NULL',
                'page_title'        => 'varchar(250) NOT NULL',
                'no_index'          => 'int(1) NOT NULL DEFAULT "0"',
                'sort'              => 'integer NOT NULL DEFAULT "1"'
            ],
            $this->getOptions()
        );

        $this->createIndex("ux_{{article_article}}_alias_lang", '{{article_article}}', "alias,lang", true);
        $this->createIndex("ix_{{article_article}}_status", '{{article_article}}', "status", false);
        $this->createIndex("ix_{{article_article}}_user_id", '{{article_article}}', "user_id", false);
        $this->createIndex("ix_{{article_article}}_category_id", '{{article_article}}', "category_id", false);
        $this->createIndex("ix_{{article_article}}_date", '{{article_article}}', "date", false);

        //fk
        $this->addForeignKey(
            "fk_{{article_article}}_user_id",
            '{{article_article}}',
            'user_id',
            '{{user_user}}',
            'id',
            'SET NULL',
            'NO ACTION'
        );
        $this->addForeignKey(
            "fk_{{article_article}}_category_id",
            '{{article_article}}',
            'category_id',
            '{{category_category}}',
            'id',
            'SET NULL',
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropTableWithForeignKeys('{{article_article}}');
    }
}
