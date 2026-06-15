<?php

namespace App\Controller\Platform\Backend;

use App\Controller\Platform\PlatformController;
use App\Entity\Platform\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(User::ROLE_USER)]
class SupportController extends PlatformController
{
    #[Route('/{_locale}/admin/v1/support', name: 'admin_v1_sys_support')]
    public function index(Request $request): Response
    {
        return $this->render('platform/backend/v1/support/index.html.twig', [
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu('system'),
            'title' => 'Support',
        ]);
    }

    #[Route('/{_locale}/admin/v1/support/development', name: 'admin_v1_sys_support_development')]
    public function development(): Response
    {
        return $this->render('platform/backend/v1/content.html.twig', [
            'title' => 'Fejlesztési ütemterv',
            'content' => '
                <h5><i class="bi bi-check-square"></i> Elkészült fejlesztés(ek):</h5>
                    <ul>
                        <li>
                            <b>új rendelés értesítés</b>
                            <br>SHOP > Rendelések > Új rendelések alatt az <code>enable audio notificiation</code> gomb segítségével aktiválható egy hangértesítő
                            <br><span class="small text-muted">2026.06.04.</span>
                        </li>
                        <li>
                            <b>YouTube [shortcode]</b>
                            <br><small>Szövegszerkesztőben reszponzív módon beágyazható YouTube videó shortcode segítségével, például:
                            <br><code>[shortcode type="youtube" value="https://www.youtube.com/watch?v=..."]</code></small>
                            <br><span class="small text-muted">2026.05.27.</span>
                        </li>
                    </ul>
                    <h5><i class="bi bi-hourglass-split"></i> Folyamatban lévő fejlesztés(ek):</h5>
                    <ul>
                        <li>Rendelés e-mail sablonokba különböző behelyettesíthető változók hozzáadása (pl. rendelés azonosító, termék neve, mennyiség, ár, stb.).
                            <br><span class="small text-muted">Tervezett átadás: 2026.06.11.</span>
                        </li>
                    </ul>
                    <h5><i class="bi bi-lightbulb"></i> További tervezett fejlesztés(ek):</h5>
                    <ul>
                    <li>Webshop felhasználói profil oldalak hozzáadása (pl. rendelések, számlák, stb.).</li>
                </ul>
            ',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
        ]);
    }

    #[Route('/{_locale}/admin/v1/support/help', name: 'admin_v1_sys_support_help')]
    public function help(): Response
    {
        return $this->render('platform/backend/v1/content.html.twig', [
            'title' => 'Súgó',
            'content' => '
                <ul>
                    <li>A PLATFORM egy olyan megoldást használ, mellyel nemcsak a honlapok, webáruházak gyors betöltődését biztosítja, hanem azok hacker támadása elleni védelmet is biztonságosabban nyújt. Ennek hátránya, hogy a közzétett vagy módosított tartalmak (bejegyzés, oldal, termék, menüpont, stb.) nem jelennek meg automatikusan az adott domain alatt, hanem manuálisan kell publikálni az újdonságot. Erre jelenleg a <code>Vezérlőpult &gt; Szolgáltatások &gt; Honlap</code> menüpontban az adott domain végén található <code>&middot;&middot;&middot;</code> lenyitva a <code>&lt;/&gt; Deploy</code> segítségével van mód. Ezen (gyakorlatilag tömeges tartalomfrissítési) megoldás helyett folyamatban van egy olyan megvalósítás, ahol az egyes szerkesztéseknél két <code>submit</code> típusú gomb lesz: egy <strong>Mentés</strong>, amely csak elmenti adatbázisba, de nem teszi közé a honlapon és egy <strong>Közzététel</strong>, mely nemcsak elmenti, de egyúttal a frontendet is lefrissíti.</li>
                    <li>Van lehetőség tesztfelületen (Platform Demo) gyakorolni, ismerkedni a kezelőfelülettel, bár folyamatos fejlesztés alatt áll jelenleg is. A demo felületre történő váltásra a jobb felső személyes menüpont lenyitását követően a <code>Vállalkozások és szervezetek</code> alatt van lehetőség.</li>
                </ul>
            ',
            'sidebarMenu' => $this->getSidebarController()->getSidebarMenu(),
        ]);
    }

}
