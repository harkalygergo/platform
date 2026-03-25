<?php

namespace App\DataFixtures;

use App\Entity\Platform\Template;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $templateFixtures = [
            ['Alpha &alpha;', 'alpha', 'pure empty template', 1, 'alpha.jpg', 1],
            ['Beta &beta;', 'beta', 'basic template with header and footer', 2, 'beta.jpg', 1],
            ['Gamma &gamma;', 'gamma', 'advanced template with sidebar and widgets, ideal for CV', 3, 'gamma.jpg', 1],
            ['Delta &delta;', 'delta', 'ideal for events and conferences', 4, 'delta.jpg', 1],
            ['Epsilon &epsilon;', 'epsilon', 'ideal for webshop', 5, 'epsilon.jpg', 1],
            ['Zeta &zeta;', 'zeta', '', 6, 'zeta.jpg', 1],
            ['Eta &eta;', 'eta', '', 7, 'eta.jpg', 1],
            ['Theta &theta;', 'theta', '', 8, 'theta.jpg', 1],
            ['Iota &iota;', 'iota', '', 9, 'iota.jpg', 1],
            ['Kappa &kappa;', 'kappa', '', 10, 'kappa.jpg', 1],
            ['Lambda &lambda;', 'lambda', '', 11, 'lambda.jpg', 1],
            ['Mu &mu;', 'mu', '', 12, 'mu.jpg', 1],
            ['Nu &nu;', 'nu', '', 13, 'nu.jpg', 1],
            ['Xi &xi;', 'xi', '', 14, 'xi.jpg', 1],
            ['Omicron &omicron;', 'omicron', '', 15, 'omicron.jpg', 1],
            ['Pi &pi;', 'pi', '', 16, 'pi.jpg', 1],
            ['Rho &rho;', 'rho', '', 17, 'rho.jpg', 1],
            ['Sigma &sigma;', 'sigma', '', 18, 'sigma.jpg', 1],
            ['Tau &tau;', 'tau', '', 19, 'tau.jpg', 1],
            ['Upsilon &upsilon;', 'upsilon', '', 20, 'upsilon.jpg', 1],
            ['Phi &phi;', 'phi', '', 21, 'phi.jpg', 1],
            ['Chi &chi;', 'chi', '', 22, 'chi.jpg', 1],
            ['Psi &psi;', 'psi', '', 23, 'psi.jpg', 1],
            ['Omega &omega;', 'omega', '', 24, 'omega.jpg', 1],
        ];

        foreach ($templateFixtures as $templateFixture) {
            $template = new Template();
            $template->setName($templateFixture[0]);
            $template->setCode($templateFixture[1]);
            $template->setDescription($templateFixture[2]);
            $template->setPosition($templateFixture[3]);
            $template->setImagePath($templateFixture[4]);
            $template->setIsActive($templateFixture[5]);

            $manager->persist($template);
        }

        $manager->flush();
    }
}
