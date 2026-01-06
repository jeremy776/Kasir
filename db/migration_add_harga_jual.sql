USE `db_kasir`;

ALTER TABLE `tb_nota`
ADD COLUMN IF NOT EXISTS `harga_jual` INT(11) NOT NULL DEFAULT 0 COMMENT 'Harga saat transaksi';

UPDATE `tb_nota` n
JOIN `produk` p ON n.idproduk = p.idproduk
SET
    n.harga_jual = p.harga_jual
WHERE
    n.harga_jual = 0;