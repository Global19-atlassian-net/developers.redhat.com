<?php

use Phinx\Migration\AbstractMigration;

class PublishedAtMigration extends AbstractMigration
{
    /**
     * Change Method.
     * Copy published_date to published_at field, then copy created to published_at if its still null.
     */
    public function change()
    {
        $this->adapter->beginTransaction();
            $this->execute("UPDATE drupal.lightning_node_field_data, drupal.lightning_node__field_published_date SET published_at = UNIX_TIMESTAMP(STR_TO_DATE(drupal.lightning_node__field_published_date.field_published_date_value, '%Y-%m-%d %h:%i%p')) WHERE nid = drupal.lightning_node__field_published_date.entity_id AND vid = revision_id;");
            $this->execute("UPDATE drupal.lightning_node_field_revision, drupal.lightning_node__field_published_date SET published_at = UNIX_TIMESTAMP(STR_TO_DATE(drupal.lightning_node__field_published_date.field_published_date_value, '%Y-%m-%d %h:%i%p')) WHERE nid = drupal.lightning_node__field_published_date.entity_id;");
            $this->execute("UPDATE drupal.lightning_node_field_data SET published_at = created WHERE published_at IS NULL;");
            $this->execute("UPDATE drupal.lightning_node_field_revision SET published_at = created WHERE published_at IS NULL;");
        $this->adapter->commitTransaction();
    }
}