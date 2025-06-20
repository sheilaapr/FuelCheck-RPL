-- Tambahkan Foreign Key spbu_id pada ulasan_spbu jika belum ada
ALTER TABLE ulasan_spbu
ADD CONSTRAINT fk_spbu FOREIGN KEY (spbu_id) REFERENCES spbu(id);
