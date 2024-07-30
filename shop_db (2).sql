-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2024 at 04:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` decimal(10,3) NOT NULL,
  `placed_on` varchar(50) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(42, 34, 'ka boy', '0888292031', 'kagem@gmail.com', 'cash on delivery', 'di jalan yg benar , jakarta, Indonesia', ',Oranges (per kg) ( 1 ),Pakcoy (250 gr) ( 1 )', 18.000, '29-Jul-2024', 'completed'),
(43, 34, 'ka gem', '888292031', 'kagem@gmail.com', 'cash on delivery', 'di jalan yg benar , jakarta, India', ',Ikan Tongkol Frozen (1 kg) ( 1 ),Ikan Shisamo Premium Import ( 1 )', 70.000, '29-Jul-2024', 'completed'),
(44, 42, 'ka gem', '888292031', 'kagem@gmail.com', 'cash on delivery', 'di jalan yg benar , jakarta, India', ',Oranges (per kg) ( 1 ),Apel Fuji (per kg) ( 1 )', 45.000, '29-Jul-2024', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(20) NOT NULL,
  `details` varchar(500) NOT NULL,
  `price` decimal(10,3) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `details`, `price`, `image`) VALUES
(28, 'Apel Fuji (per kg)', 'fruits', 'Nikmati kesegaran dan rasa manis dari Apel Fuji yang baru dipetik. Apel ini dikenal dengan tekstur renyah dan rasa yang lezat, cocok untuk dimakan langsung atau digunakan dalam berbagai resep.\r\n', 33.000, 'apple-fuji.png'),
(29, 'Oranges (per kg)', 'fruits', 'Rasakan kesegaran dan kelezatan Jeruk Mandarin yang baru dipetik. Dengan rasa manis dan sedikit asam, jeruk ini sangat cocok untuk dinikmati langsung, dijadikan jus, atau sebagai bahan tambahan dalam berbagai hidangan.      \r\n                                                   \r\n', 12.000, 'orange.png'),
(30, 'Alpukat (per kg)', 'fruits', 'Nikmati kelezatan dan kriminess dari alpukat  yang segar. Alpukat ini memiliki daging yang lembut dan rasa yang kaya, ideal untuk salad, guacamole, atau sebagai topping sehat untuk berbagai hidangan.\r\n\r\n', 10.000, 'alpukat.png'),
(31, 'Mangga (per kg)', 'fruits', 'Nikmati kelezatan manis dan juicy dari mangga yang segar. Mangga ini memiliki daging yang lembut dan rasa yang tropis, cocok untuk dinikmati langsung, dibuat jus, atau sebagai bahan tambahan dalam berbagai hidangan.\r\n\r\n', 15.000, 'mangga.png'),
(32, 'Naga (per kg)', 'fruits', 'Rasakan keunikan dan kesegaran dari buah naga yang eksotis. Buah naga ini dikenal dengan dagingnya yang lembut dan rasa yang manis, dengan warna yang cerah dan penampilan yang menarik.\r\n\r\n', 14.800, 'naga.png'),
(33, 'Pisang (per kg)', 'fruits', 'Nikmati rasa manis dan tekstur lembut dari pisang yang segar. Pisang ini ideal untuk dimakan langsung, dibuat smoothie, atau digunakan dalam berbagai resep kue dan makanan penutup.\r\n\r\n', 22.000, 'banana.png'),
(34, 'Anggur (per kg)', 'fruits', 'Nikmati kesegaran dan rasa manis dari anggur  yang baru dipetik. Anggur ini memiliki daging buah yang juicy dan kulit yang renyah, sempurna untuk dinikmati langsung, digunakan dalam salad, atau sebagai camilan sehat.\r\n\r\n', 33.500, 'blue grapes.png'),
(35, 'Semangka (per kg)', 'fruits', 'Rasakan kesegaran dan kelezatan semangka yang juicy dan manis. Semangka ini adalah pilihan sempurna untuk menghilangkan dahaga di hari panas atau sebagai tambahan segar dalam berbagai hidangan.\r\n\r\n', 20.000, 'watermelon.png'),
(36, 'Brokoli (250 gr)', 'vegetables', 'Nikmati manfaat kesehatan dan kesegaran dari brokoli segar kami. Brokoli ini dikenal dengan tekstur renyah dan rasa yang sedikit pahit, ideal untuk dimasak, ditambahkan dalam salad, atau sebagai camilan sehat.\r\n\r\n', 8.900, 'broccoli.png'),
(37, 'Pakcoy (250 gr)', 'vegetables', 'Nikmati kesegaran dan kelezatan Pak Choi kami. Sayuran ini dikenal dengan daun hijau yang renyah dan batang putih yang juicy, ideal untuk berbagai hidangan Asia atau sebagai tambahan sehat dalam masakan sehari-hari.', 5.900, 'pack-choi.png'),
(40, 'Cabe Rawit Merah (100 gr)', 'vegetables', 'Rasakan kepedasan dan kesegaran dari cabe rawit segar kami. Cabe ini dikenal dengan rasa pedas yang intens dan aroma yang khas, ideal untuk menambah cita rasa pada berbagai hidangan, terutama masakan Asia dan sambal.', 10.900, 'cabe rawit.jpg'),
(41, 'Kol (1 kg)', 'vegetables', 'Nikmati kesegaran dan manfaat dari kol segar kami. Kol ini dikenal dengan daun hijau yang renyah dan rasa yang sedikit manis, ideal untuk berbagai hidangan seperti salad, sup, dan tumisan.', 8.000, 'cabbage.png'),
(42, 'Tomat (250 gr)', 'vegetables', 'Nikmati kesegaran dan rasa dari tomat segar kami. Tomat ini dikenal dengan dagingnya yang juicy dan rasa yang manis dengan sedikit asam, ideal untuk berbagai hidangan seperti salad, saus, dan sup.', 6.000, 'tomato.png'),
(43, 'Beef Wagyu Saikoro Cubes Meltique (500 gr)', 'meat', 'Nikmati kelezatan dan kelembutan Wagyu Saikoro Cubes kami. Daging Wagyu berkualitas tinggi ini dipotong dalam bentuk dadu, ideal untuk berbagai hidangan seperti steak dan BBQ.', 85.000, 'wagyu saikoro.jpg'),
(45, 'MINCED BEEF', 'meat', 'Nikmati kualitas dan kelezatan Minced Beef kami yang segar. Daging sapi cincang ini ideal untuk berbagai hidangan seperti burger, meatballs, atau bolognese, memberikan rasa yang kaya dan tekstur yang lembut.', 29.000, 'minced beef.jpg'),
(46, 'Dada Ayam Fillet (1 kg)', 'meat', 'Nikmati kesegaran dan kelezatan Dada Ayam Fillet kami. Dada ayam tanpa tulang dan kulit ini sangat cocok untuk berbagai hidangan sehat seperti salad, stir-fry, atau grilled chicken.', 40.900, 'dada ayam.jpg'),
(47, 'Daging Ayam Paha Fillet', 'meat', 'Nikmati kesegaran dan kelezatan Paha Ayam Fillet kami. Dada ayam tanpa tulang dan kulit ini sangat cocok untuk berbagai hidangan sehat seperti salad, stir-fry, atau grilled chicken.', 42.000, 'paha ayam.jpg'),
(52, 'Ikan Kembung Frozen ', 'fish', 'Nikmati kesegaran dan rasa dari Ikan Kembung kami. Ikan ini dikenal dengan dagingnya yang lembut dan rasa yang kaya, ideal untuk berbagai hidangan seperti panggang, bakar goreng.', 31.000, 'ikan kembung.jpg'),
(53, 'Ikan Tongkol Frozen (1 kg)', 'fish', 'Nikmati kesegaran dan rasa dari Ikan Tongkol kami. Ikan ini dikenal dengan dagingnya yang bertekstur padat dan rasa yang kaya, ideal untuk berbagai hidangan seperti panggang, bakar, atau dalam hidangan kari.', 30.000, 'ikan tongkoll.jpg'),
(54, 'Ikan Shisamo Premium Import', 'fish', 'Nikmati kesegaran dan kelezatan Shisamo kami. Shisamo adalah ikan kecil yang sangat populer dalam masakan Jepang, dikenal dengan rasa yang gurih dan tekstur yang lembut, ideal untuk dipanggang dan digoreng.', 40.000, 'ikan shisamo.jpg'),
(55, 'Ikan Salmon Trout Fillet (200 gr)', 'fish', 'Nikmati kelezatan dan keunggulan Salmon kami. Dikenal dengan dagingnya yang lembut salmon dan berwarna oranye, sangat ideal untuk hidangan seperti sashimi,salmon dan salmon panggang.', 66.000, 'ikan salmon.jpg'),
(56, 'Ikan Makarel (1 kg)', 'fish', 'Makarel adalah jenis ikan yang termasuk dalam keluarga Scombridae, yang dikenal karena dagingnya yang lezat dan bergizi', 33.000, 'ikan makarel.png'),
(57, 'Ikan Tuna (500 gr)', 'fish', 'Tongkol adalah jenis ikan yang termasuk dalam keluarga Scombridae, sama seperti makarel, dan dikenal karena dagingnya yang lezat dan bergizi', 35.000, 'ikan tongkol.jpg'),
(58, 'Beef Steek', 'meat', 'Nikmati kelezatan beef steak yang sempurna, terbuat dari potongan daging sapi premium yang dipilih dengan teliti untuk kualitas terbaik. Setiap steak dibumbui dengan campuran rempah yang harmonis dan dipanggang hingga mencapai tingkat kematangan yang diinginkan, mulai dari rare hingga well-done.', 120.000, 'beef steek.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user',
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`, `image`) VALUES
(41, 'Xaviera Putri', 'admin01@gmail.com', '$2y$10$avpfTqBLnbdIozSSb.shEOmtFZuKHewxAUcZ0yN44R8.mHmHNjb2O', 'admin', 'pic-7.png'),
(42, 'Anselma Putri', 'user01@gmail.com', '$2y$10$dWfpgAESNe.v97tbHuN3d.kmkDgtGJ8h1XAeXEUAItpnAHqyJ2pka', 'user', 'pic-1.png');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
