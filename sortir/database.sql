--
-- Structure de la table `etat`
--

DROP TABLE IF EXISTS `etat`;
CREATE TABLE IF NOT EXISTS `etat` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `libelle` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `etat`
--

INSERT INTO `etat` (`id`, `libelle`) VALUES
(1, 'open'),
(2, 'draft'),
(3, 'closed'),
(4, 'active'),
(5, 'finished'),
(6, 'canceled'),
(7, 'archived');

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

DROP TABLE IF EXISTS `inscription`;
CREATE TABLE IF NOT EXISTS `inscription` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `sortie_id` int(11) NOT NULL,
    `participant_id` int(11) NOT NULL,
    `date_inscription` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `IDX_5E90F6D6CC72D953` (`sortie_id`),
    KEY `IDX_5E90F6D69D1C3019` (`participant_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `inscription`
--

INSERT INTO `inscription` (`id`, `sortie_id`, `participant_id`, `date_inscription`) VALUES
(1, 28, 9, '2021-03-24 12:03:13');

-- --------------------------------------------------------

--
-- Structure de la table `lieu`
--

DROP TABLE IF EXISTS `lieu`;
CREATE TABLE IF NOT EXISTS `lieu` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `ville_id` int(11) NOT NULL,
    `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `adresse` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
    PRIMARY KEY (`id`),
    KEY `IDX_2F577D59A73F0036` (`ville_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `lieu`
--

INSERT INTO `lieu` (`id`, `ville_id`, `nom`, `adresse`) VALUES
(1, 1, 'Square', '30 rue du Gue Jacquet'),
(2, 2, 'Patinoire', '45 Rue de la Pompe'),
(3, 3, 'Bowling', '79 rue de la Boétie'),
(4, 4, 'Centre ville', '56 rue de l Epeule'),
(5, 6, 'Cinema', '26 rue Michel Ange'),
(6, 6, 'Theatre', '1 rue Saint Germain'),
(7, 7, 'Square', '51 Avenue des Pres'),
(8, 8, 'Parc', '53 rue des Nations Unies'),
(9, 19, 'Terrain de foot', '74 place Stanislas'),
(10, 2, 'Centre', '24 rue de la Boétie'),
(11, 10, 'Bar a jeu', '77 rue La Boétie'),
(12, 11, 'Salle de concert', '68 rue Petite Fusterie'),
(13, 12, 'Escape game', '52 rue Sadi Carnot'),
(14, 13, 'Piste de course', '15 rue de l Aigle'),
(15, 14, 'Centre ville', '78 rue Beauvau'),
(16, 21, 'Parc', '96 Rue du Limas'),
(17, 17, 'Cyber café', '13 rue des Nations Unies'),
(18, 5, 'Plage', '1 rue de Paris'),
(19, 1, 'Square', '30 rue du Gue Jacquet'),
(20, 2, 'Patinoire', '45 Rue de la Pompe'),
(21, 3, 'Bowling', '79 rue de la Boétie'),
(22, 4, 'Centre ville', '56 rue de l Epeule'),
(23, 6, 'Cinema', '26 rue Michel Ange'),
(24, 6, 'Theatre', '1 rue Saint Germain'),
(25, 7, 'Square', '51 Avenue des Pres'),
(26, 8, 'Parc', '53 rue des Nations Unies'),
(27, 19, 'Terrain de foot', '74 place Stanislas'),
(28, 2, 'Centre', '24 rue de la Boétie'),
(29, 10, 'Bar a jeu', '77 rue La Boétie'),
(30, 11, 'Salle de concert', '68 rue Petite Fusterie'),
(31, 12, 'Escape game', '52 rue Sadi Carnot'),
(32, 13, 'Piste de course', '15 rue de l Aigle'),
(33, 14, 'Centre ville', '78 rue Beauvau'),
(34, 21, 'Parc', '96 Rue du Limas'),
(35, 17, 'Cyber café', '13 rue des Nations Unies'),
(36, 5, 'Plage', '1 rue de Paris');

-- --------------------------------------------------------

--
-- Structure de la table `site`
--

DROP TABLE IF EXISTS `site`;
CREATE TABLE IF NOT EXISTS `site` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nom` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `site`
--

INSERT INTO `site` (`id`, `nom`) VALUES
(1, 'Rennes'),
(2, 'Quimper'),
(3, 'Le Mans'),
(4, 'Angers'),
(5, 'Nantes'),
(6, 'La Roche-sur-Yon'),
(7, 'Niort'),
(8, 'Rennes'),
(9, 'Quimper'),
(10, 'Le Mans'),
(11, 'Angers'),
(12, 'Nantes'),
(13, 'La Roche-sur-Yon'),
(14, 'Niort');

-- --------------------------------------------------------

--
-- Structure de la table `sortie`
--

DROP TABLE IF EXISTS `sortie`;
CREATE TABLE IF NOT EXISTS `sortie` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `etat_id` int(11) NOT NULL,
    `organisateur_id` int(11) NOT NULL,
    `lieu_id` int(11) NOT NULL,
    `site_id` int(11) NOT NULL,
    `ville_id` int(11) NOT NULL,
    `nom` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
    `date_debut` datetime NOT NULL,
    `duree` int(11) NOT NULL,
    `date_cloture` datetime NOT NULL,
    `nb_inscription_max` int(11) NOT NULL,
    `commentaire` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `motif` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `IDX_3C3FD3F2D5E86FF` (`etat_id`),
    KEY `IDX_3C3FD3F2D936B2FA` (`organisateur_id`),
    KEY `IDX_3C3FD3F26AB213CC` (`lieu_id`),
    KEY `IDX_3C3FD3F2F6BD1646` (`site_id`),
    KEY `IDX_3C3FD3F2A73F0036` (`ville_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sortie`
--

INSERT INTO `sortie` (`id`, `etat_id`, `organisateur_id`, `lieu_id`, `site_id`, `ville_id`, `nom`, `date_debut`, `duree`, `date_cloture`, `nb_inscription_max`, `commentaire`, `motif`) VALUES
(49, 6, 1, 1, 1, 1, 'balade', '2021-04-02 16:29:00', 120, '2021-03-25 00:00:00', 14, 'balade pour se rencontrer au square', NULL),
(50, 2, 2, 2, 1, 2, 'sortie patinoire', '2021-04-02 16:29:00', 90, '2021-03-25 00:00:00', 10, 'sortie pour patiner tranquillement', NULL),
(51, 6, 3, 3, 1, 3, 'sortie bowilng', '2021-04-02 16:29:00', 90, '2021-03-25 00:00:00', 8, 'sortie pour lancer quelques boules', NULL),
(52, 1, 11, 4, 1, 4, 'Promenade', '2021-04-02 16:29:00', 120, '2021-03-25 00:00:00', 14, 'petite promenade OKLM', NULL),
(53, 2, 4, 5, 1, 5, 'Petit film', '2021-04-02 16:29:00', 100, '2021-03-25 00:00:00', 50, 'pour ceux qui veulent voir les tuchs 6', NULL),
(54, 2, 5, 6, 1, 6, 'Sortie mondaine', '2021-05-02 18:29:00', 180, '2021-04-25 00:00:00', 2, 'petite pièce de théatre (bien habillé s il vous plait)', NULL),
(55, 1, 6, 7, 1, 7, 'Pic-nique', '2021-04-28 11:45:00', 180, '2021-04-26 00:00:00', 12, 'Venez manger dans l herbe', NULL),
(56, 1, 7, 8, 1, 8, 'Promenade', '2021-04-26 09:50:00', 400, '2021-04-23 00:00:00', 12, 'Grosse balade', NULL),
(57, 6, 8, 9, 1, 19, 'Petit foot', '2021-04-02 14:29:00', 200, '2021-03-25 00:00:00', 20, 'On va taper la balle', NULL),
(58, 2, 9, 10, 1, 2, 'Découverte de la ville', '2021-04-02 16:29:00', 200, '2021-03-25 00:00:00', 20, 'Pour ceux qui ne connaissent pas encore la ville', NULL),
(59, 6, 10, 11, 1, 10, 'Soirée jeu de société', '2021-04-02 16:29:00', 180, '2021-03-25 00:00:00', 8, 'Pour ceux qui veulent passer 5h sur un risk', NULL),
(60, 1, 1, 12, 1, 11, 'Concert ce soir', '2021-04-02 16:29:00', 140, '2021-03-25 00:00:00', 25, 'Concert super sympa !', NULL),
(61, 1, 2, 13, 1, 12, 'Escape game', '2021-04-17 16:29:00', 90, '2021-04-15 00:00:00', 5, 'Venez utiliser vos petites cellules grises ;)', NULL),
(62, 1, 3, 14, 1, 13, 'Pour les sportifs', '2021-04-02 16:29:00', 60, '2021-03-25 00:00:00', 10, 'Une heure de tour de piste', NULL),
(63, 5, 4, 15, 1, 14, 'Sortie shopping', '2021-03-23 09:49:00', 80, '2021-03-20 00:00:00', 6, 'On refait le diable d habille en prada ', NULL),
(64, 1, 5, 16, 1, 21, 'Barbecue', '2021-04-26 09:50:00', 150, '2021-04-24 00:00:00', 20, 'Ramenez vos saucisses', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `site_id` int(11) DEFAULT NULL,
    `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `telephone` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `mail` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `admin` tinyint(1) NOT NULL,
    `actif` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `UNIQ_1D1C63B3F85E0677` (`username`),
    UNIQUE KEY `UNIQ_1D1C63B35126AC48` (`mail`),
    KEY `IDX_1D1C63B3F6BD1646` (`site_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `site_id`, `username`, `nom`, `prenom`, `telephone`, `mail`, `password`, `admin`, `actif`) VALUES
(1, 1, 'cbreyne', 'Breyne', 'Clement', '0123456789', 'clement.breyne@gmail.com', '$2y$10$kHwVDeU16JKYOzzVQSgUzuAqs/9ENQkvrc6Jzf1MQoJTZwQtF8JIa', 1, 1),
(2, 1, 'abaron', 'Baron', 'Alexandre', '0123456789', 'alexandre@gmail.com', '$2y$10$kHwVDeU16JKYOzzVQSgUzuAqs/9ENQkvrc6Jzf1MQoJTZwQtF8JIa', 1, 1),
(3, 1, 'jbfilleul', 'Filleul', 'Jean-Baptiste', '0123456789', 'jeanBaptiste@gmail.com', '$2y$10$kHwVDeU16JKYOzzVQSgUzuAqs/9ENQkvrc6Jzf1MQoJTZwQtF8JIa', 1, 1),
(4, 1, 'wgilot', 'Gilot', 'William', '0123456789', 'william@gmail.com', '$2y$10$kHwVDeU16JKYOzzVQSgUzuAqs/9ENQkvrc6Jzf1MQoJTZwQtF8JIa', 1, 1),
(5, 2, 'Lionceau de Cintra', 'Elen Riannon', 'Cirilla ', '0123456789', 'Cirilla@gmail.com', '$2y$10$kHwVDeU16JKYOzzVQSgUzuAqs/9ENQkvrc6Jzf1MQoJTZwQtF8JIa', 2, 1),
(6, 3, 'la quatorzième du Mont', 'Merigold', 'Triss ', '0123456789', 'Triss@gmail.com', '$2y$10$kHwVDeU16JKYOzzVQSgUzuAqs/9ENQkvrc6Jzf1MQoJTZwQtF8JIa', 2, 1),
(7, 4, 'Le mage', 'de Roggeveen', 'Vilgefortz ', '0123456789', 'Vilgefortz@gmail.com', '$2y$10$kHwVDeU16JKYOzzVQSgUzuAqs/9ENQkvrc6Jzf1MQoJTZwQtF8JIa', 2, 1),
(8, 5, 'Deithwen Addan yn Carn aep Morvudd', 'var Emreis', 'Emhyr  ', '0123456789', 'Emhyr@gmail.com', '$2y$10$kHwVDeU16JKYOzzVQSgUzuAqs/9ENQkvrc6Jzf1MQoJTZwQtF8JIa', 2, 1),
(9, 6, 'LeoBonhart', 'Bonhart', 'Léo ', '0123456789', 'Léo@gmail.com', '$2y$10$kHwVDeU16JKYOzzVQSgUzuAqs/9ENQkvrc6Jzf1MQoJTZwQtF8JIa', 2, 1),
(10, 3, 'Milva', 'Barring', 'Maria ', '0123456789', 'Maria@gmail.com', '$2y$10$kHwVDeU16JKYOzzVQSgUzuAqs/9ENQkvrc6Jzf1MQoJTZwQtF8JIa', 2, 1),
(11, 4, 'Carreras', 'Metz', 'Keira  ', '0123456789', 'Keira@gmail.com', '$2y$10$kHwVDeU16JKYOzzVQSgUzuAqs/9ENQkvrc6Jzf1MQoJTZwQtF8JIa', 2, 1),
(12, 6, 'Lauteur', 'Sapkowski ', 'Andrzej  ', '0123456789', 'Andrzej@gmail.com', '$2y$10$kHwVDeU16JKYOzzVQSgUzuAqs/9ENQkvrc6Jzf1MQoJTZwQtF8JIa', 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `ville`
--

DROP TABLE IF EXISTS `ville`;
CREATE TABLE IF NOT EXISTS `ville` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nom` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
    `code_postal` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ville`
--

INSERT INTO `ville` (`id`, `nom`, `code_postal`) VALUES
(1, 'Nantes', '44000'),
(2, 'Rennes', '35000'),
(3, 'Angers', '49000'),
(4, 'Le Mans', '72000'),
(5, 'Brest', '29200'),
(6, 'Caen', '14000'),
(7, 'Saint-Nazaire', '44600'),
(8, 'Quimper', '29000'),
(9, 'Lorient', '56100'),
(10, 'Cholet', '49300'),
(11, 'Vannes', '56000'),
(12, 'La Roche-sur-Yon', '85000'),
(13, 'Laval', '53000'),
(14, 'Saint-Malo', '35400'),
(15, 'Saint-Brieuc', '22000'),
(16, 'Saint-Herblain', '448000'),
(17, 'Cherbourg', '50100'),
(18, 'Rezé', '44400'),
(19, 'Saumur', '49400'),
(20, 'Alençon', '61000'),
(21, 'Rouen', '76000'),
(22, 'Montpellier', '34000'),
(23, 'Chartres-de-Bretagne', '35131'),
(24, 'Nantes', '44000'),
(25, 'Rennes', '35000'),
(26, 'Angers', '49000'),
(27, 'Le Mans', '72000'),
(28, 'Brest', '29200'),
(29, 'Caen', '14000'),
(30, 'Saint-Nazaire', '44600'),
(31, 'Quimper', '29000'),
(32, 'Lorient', '56100'),
(33, 'Cholet', '49300'),
(34, 'Vannes', '56000'),
(35, 'La Roche-sur-Yon', '85000'),
(36, 'Laval', '53000'),
(37, 'Saint-Malo', '35400'),
(38, 'Saint-Brieuc', '22000'),
(39, 'Saint-Herblain', '448000'),
(40, 'Cherbourg', '50100'),
(41, 'Rezé', '44400'),
(42, 'Saumur', '49400'),
(43, 'Alençon', '61000'),
(44, 'Rouen', '76000'),
(45, 'Montpellier', '34000'),
(46, 'Chartres-de-Bretagne', '35131');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `inscription`
--
ALTER TABLE `inscription`
    ADD CONSTRAINT `FK_5E90F6D69D1C3019` FOREIGN KEY (`participant_id`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `FK_5E90F6D6CC72D953` FOREIGN KEY (`sortie_id`) REFERENCES `sortie` (`id`);

--
-- Contraintes pour la table `lieu`
--
ALTER TABLE `lieu`
    ADD CONSTRAINT `FK_2F577D59A73F0036` FOREIGN KEY (`ville_id`) REFERENCES `ville` (`id`);

--
-- Contraintes pour la table `sortie`
--
ALTER TABLE `sortie`
    ADD CONSTRAINT `FK_3C3FD3F26AB213CC` FOREIGN KEY (`lieu_id`) REFERENCES `lieu` (`id`),
  ADD CONSTRAINT `FK_3C3FD3F2A73F0036` FOREIGN KEY (`ville_id`) REFERENCES `ville` (`id`),
  ADD CONSTRAINT `FK_3C3FD3F2D5E86FF` FOREIGN KEY (`etat_id`) REFERENCES `etat` (`id`),
  ADD CONSTRAINT `FK_3C3FD3F2D936B2FA` FOREIGN KEY (`organisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_3C3FD3F2F6BD1646` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
    ADD CONSTRAINT `FK_1D1C63B3F6BD1646` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
