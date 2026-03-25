<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260325165337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO `_template` (`id`, `name`, `code`, `description`, `position`, `image_path`, `is_active`) VALUES
(1, 'Alpha &alpha;', 'alpha', 'pure empty template', 1, 'alpha.jpg', 1),
(2, 'Beta &beta;', 'beta', 'basic template with header and footer', 2, 'beta.jpg', 1),
(3, 'Gamma &gamma;', 'gamma', 'advanced template with sidebar and widgets, ideal for CV', 3, 'gamma.jpg', 1),
(4, 'Delta &delta;', 'delta', 'ideal for events and conferences', 4, 'delta.jpg', 1),
(5, 'Epsilon &epsilon;', 'epsilon', 'ideal for webshop', 5, 'epsilon.jpg', 1),
(6, 'Zeta &zeta;', 'zeta', '', 6, 'zeta.jpg', 1),
(7, 'Eta &eta;', 'eta', '', 7, 'eta.jpg', 1),
(8, 'Theta &theta;', 'theta', '', 8, 'theta.jpg', 1),
(9, 'Iota &iota;', 'iota', '', 9, 'iota.jpg', 1),
(10, 'Kappa &kappa;', 'kappa', '', 10, 'kappa.jpg', 1),
(11, 'Lambda &lambda;', 'lambda', '', 11, 'lambda.jpg', 1),
(12, 'Mu &mu;', 'mu', '', 12, 'mu.jpg', 1),
(13, 'Nu &nu;', 'nu', '', 13, 'nu.jpg', 1),
(14, 'Xi &xi;', 'xi', '', 14, 'xi.jpg', 1),
(15, 'Omicron &omicron;', 'omicron', '', 15, 'omicron.jpg', 1),
(16, 'Pi &pi;', 'pi', '', 16, 'pi.jpg', 1),
(17, 'Rho &rho;', 'rho', '', 17, 'rho.jpg', 1),
(18, 'Sigma &sigma;', 'sigma', '', 18, 'sigma.jpg', 1),
(19, 'Tau &tau;', 'tau', '', 19, 'tau.jpg', 1),
(20, 'Upsilon &upsilon;', 'upsilon', '', 20, 'upsilon.jpg', 1),
(21, 'Phi &phi;', 'phi', '', 21, 'phi.jpg', 1),
(22, 'Chi &chi;', 'chi', '', 22, 'chi.jpg', 1),
(23, 'Psi &psi;', 'psi', '', 23, 'psi.jpg', 1),
(24, 'Omega &omega;', 'omega', '', 24, 'omega.jpg', 1);
    ");
        $this->addSql("ALTER TABLE `_template` MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('TRUNCATE TABLE `_template`');
    }
}
