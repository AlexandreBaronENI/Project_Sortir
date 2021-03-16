START TRANSACTION;
-- DROP DATABASE database_name;

-- CREATE DATABASE database_name;
--
-- Base de données : `database_name`
--

-- --------------------------------------------------------

--
-- Structure de la table `etat`
--

CREATE TABLE `etat` (
                        `id` int(11) NOT NULL,
                        `libelle` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

CREATE TABLE `inscription` (
                               `id` int(11) NOT NULL,
                               `sortie_id` int(11) NOT NULL,
                               `participant_id` int(11) NOT NULL,
                               `date_inscription` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lieu`
--

CREATE TABLE `lieu` (
                        `id` int(11) NOT NULL,
                        `ville_id` int(11) NOT NULL,
                        `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `adresse` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `site`
--

CREATE TABLE `site` (
                        `id` int(11) NOT NULL,
                        `nom` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sortie`
--

CREATE TABLE `sortie` (
                          `id` int(11) NOT NULL,
                          `lieu_id` int(11) NOT NULL,
                          `etat_id` int(11) NOT NULL,
                          `site_id` int(11) NOT NULL,
                          `organisateur_id` int(11) NOT NULL,
                          `nom` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
                          `duree` int(11) NOT NULL,
                          `nb_inscription_max` int(11) NOT NULL,
                          `commentaire` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                          `date_debut` datetime NOT NULL,
                          `date_cloture` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
                               `id` int(11) NOT NULL,
                               `site_id` int(11) DEFAULT NULL,
                               `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `prenom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `telephone` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                               `mail` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `admin` tinyint(1) NOT NULL,
                               `actif` tinyint(1) NOT NULL,
                               `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ville`
--

CREATE TABLE `ville` (
                         `id` int(11) NOT NULL,
                         `nom` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `code_postal` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `etat`
--
ALTER TABLE `etat`
    ADD PRIMARY KEY (`id`);

--
-- Index pour la table `inscription`
--
ALTER TABLE `inscription`
    ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5E90F6D6CC72D953` (`sortie_id`),
  ADD KEY `IDX_5E90F6D69D1C3019` (`participant_id`);

--
-- Index pour la table `lieu`
--
ALTER TABLE `lieu`
    ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2F577D59A73F0036` (`ville_id`);

--
-- Index pour la table `site`
--
ALTER TABLE `site`
    ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sortie`
--
ALTER TABLE `sortie`
    ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3C3FD3F26AB213CC` (`lieu_id`),
  ADD KEY `IDX_3C3FD3F2D5E86FF` (`etat_id`),
  ADD KEY `IDX_3C3FD3F2F6BD1646` (`site_id`),
  ADD KEY `IDX_3C3FD3F2D936B2FA` (`organisateur_id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1D1C63B35126AC48` (`mail`),
  ADD UNIQUE KEY `UNIQ_1D1C63B3F85E0677` (`username`),
  ADD KEY `IDX_1D1C63B3F6BD1646` (`site_id`);

--
-- Index pour la table `ville`
--
ALTER TABLE `ville`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `etat`
--
ALTER TABLE `etat`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `inscription`
--
ALTER TABLE `inscription`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `lieu`
--
ALTER TABLE `lieu`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `site`
--
ALTER TABLE `site`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sortie`
--
ALTER TABLE `sortie`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ville`
--
ALTER TABLE `ville`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `FK_3C3FD3F2D5E86FF` FOREIGN KEY (`etat_id`) REFERENCES `etat` (`id`),
  ADD CONSTRAINT `FK_3C3FD3F2D936B2FA` FOREIGN KEY (`organisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_3C3FD3F2F6BD1646` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
    ADD CONSTRAINT `FK_1D1C63B3F6BD1646` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`);
COMMIT;
