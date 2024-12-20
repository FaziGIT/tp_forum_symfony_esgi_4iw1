<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241220234634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Delete Response and creating Comment because an error appeared cause of Symfony\Component\HttpFoundation\Response';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, topic_id INTEGER NOT NULL, user_id INTEGER NOT NULL, content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_9474526C727ACA70 FOREIGN KEY (parent_id) REFERENCES comment (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_9474526C1F55203D FOREIGN KEY (topic_id) REFERENCES topic (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_9474526C727ACA70 ON comment (parent_id)');
        $this->addSql('CREATE INDEX IDX_9474526C1F55203D ON comment (topic_id)');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('DROP TABLE response');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE response (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, topic_id INTEGER NOT NULL, user_id INTEGER NOT NULL, content VARCHAR(255) NOT NULL COLLATE "BINARY", created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_3E7B0BFB727ACA70 FOREIGN KEY (parent_id) REFERENCES response (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3E7B0BFB1F55203D FOREIGN KEY (topic_id) REFERENCES topic (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_3E7B0BFBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_3E7B0BFBA76ED395 ON response (user_id)');
        $this->addSql('CREATE INDEX IDX_3E7B0BFB1F55203D ON response (topic_id)');
        $this->addSql('CREATE INDEX IDX_3E7B0BFB727ACA70 ON response (parent_id)');
        $this->addSql('DROP TABLE comment');
    }
}
