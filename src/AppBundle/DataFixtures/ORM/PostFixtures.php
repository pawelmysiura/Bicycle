<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 16.01.18
 * Time: 09:02
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Post;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PostFixtures extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $postList = [
            [
            'title' => 'Proin metus augue, tincidunt in mi.',
            'content' => 'Sed convallis diam non sapien semper, ac scelerisque mauris tempus. Proin at magna justo. Quisque non metus maximus, laoreet lorem non, porta libero. Fusce nulla ipsum, fermentum imperdiet velit nec, aliquet congue sem. Vivamus tincidunt mi vel quam pellentesque convallis. Cras at euismod eros. Sed mattis vel tortor vel eleifend. Etiam accumsan, arcu sed posuere condimentum, magna nulla efficitur nulla, a vestibulum ipsum nisl vitae lacus. Nulla venenatis metus id lorem vestibulum, nec gravida mauris fermentum. Nulla facilisi. Sed et accumsan quam, malesuada scelerisque enim. Sed lobortis egestas massa, sit amet blandit nisi suscipit a. Curabitur vestibulum lorem dui, eget ultricies est aliquam sed. Etiam sed tempor ante. Ut tellus diam, pellentesque quis ex et, mattis varius justo. Duis porta lacus eu interdum fringilla. Aenean cursus elit vel pretium vehicula. Aliquam justo dolor, lobortis id ultricies vel, volutpat nec leo. Nulla non sem sit amet dolor varius lacinia. Maecenas laoreet, metus at pharetra finibus, justo leo egestas elit, a semper ipsum purus eget sapien.',
            'category' => 'nowe',
            'tags' => ['Fugiunt', 'Qua'],
            'author' => 'admin',
            'createDate' => '2017-05-28 12:31:14',
            'publishedDate' => '2017-05-28 12:36:14'
        ],
            [
                'title' => 'Imbers sunt contencios de lotus bromium.',
                'content' => 'Brodiums cantare, tanquam salvus deus. Historias mori! A falsis, omnia altus devirginato. Nixus de neuter parma, gratia victrix! Ubi est mirabilis particula? Teres parmas ducunt ad vita. Resistere hic ducunt ad gratis barcas. Cum clabulare ridetis, omnes racanaes quaestio domesticus, grandis abaculuses. Assimilatios sunt humani generiss de camerarius nixus. Est teres torus, cesaris. A falsis, musa peritus terror. Cur ventus prarere? Abaculuss ire in amivadum! Cum canis favere, omnes consiliumes resuscitabo peritus, germanus cedriumes. Gratis mensas ducunt ad cobaltum. Historia de clemens finis, transferre candidatus! Ecce. Cum orgia messis, omnes glutenes gratia clemens, varius elogiumes. Burgus, scutum, et victrix. Exsul de pius historia, quaestio cacula! Rusticus, regius indexs interdum anhelare de fortis, talis cacula. Vae, calcaria! Fatalis fortiss ducunt ad historia. Domuss mori in hortus! Devirginatos mori! Aonidess cantare! Rationes sunt menss de mirabilis danista. Verpas manducare! Sunt danistaes imitari mirabilis, peritus cottaes. Equiso albus fortis est. Vae. Medicinas mori in grandis cirpi! Cum vortex assimilant, omnes magisteres captis ferox, velox gloses.',
                'category' => 'inne',
                'tags' => ['Hafnia', 'Palus'],
                'author' => 'Pawel',
                'createDate' => '2017-05-28 12:31:14',
                'publishedDate' => '2017-11-28 12:39:14'
            ],
            [
                'title' => 'Varius racana virtualiter contactuss omnia est.',
                'content' => 'Placidus indictio vix imperiums triticum est. Accentors prarere in gandavum! Abactors cadunt, tanquam primus bromium. Emeritis lacteas ducunt ad turpis. Abnoba de clemens boreas, promissio omnia! Cum clinias cantare, omnes decores visum bassus, clemens burguses. Ubi est grandis magister? Zeta bassus species est. Altus, primus tumultumques grauiter prensionem de dexter, varius fermium. Sunt tumultumquees demitto varius, ferox mensaes. Pol, a bene domina. Hippotoxotas mori in alter rugensis civitas! Cum musa messis, omnes deuses quaestio alter, castus specieses. Teres, bassus onuss saepe perdere de fortis, placidus repressor. Est nobilis cobaltum, cesaris. Sagas sunt coordinataes de barbatus capio. Pol, secundus domus! Gratis, bassus tumultumques virtualiter quaestio de rusticus, alter adiurator. Medicina, compater, et ignigena. Homos manducare in primus tolosa! Pol, a bene habitio, fiscina! Nutrixs velum in cubiculum! Sunt bursaes quaestio neuter, primus assimilatioes. Orgia, boreas, et gemna. A falsis, devirginato fidelis liberi. Hercle, mens bassus!, orgia! Ecce, magister! Rectors sunt caesiums de nobilis humani generis. Nobilis, emeritis pess solite prensionem de azureus, brevis contencio.',
                'category' => 'trasy',
                'tags' => ['Amicitia', 'Qua', 'Hilotaes'],
                'author' => 'Pawel',
                'createDate' => '2017-12-28 15:31:14',
                'publishedDate' => '2017-12-28 12:31:14'
            ],
            [
                'title' => 'Nuclear vexatum iacere de neuter terror, manifestum mons.',
                'content' => 'Omnia, abactus, et axona. Nocere sensim ducunt ad audax mons. Terrors persuadere in virundum! Orgia de bi-color lacta, tractare demissio! Ubi est emeritis compater? Agripetas volare in fatalis divio! Abnoba de talis vita, imperium triticum! Pol, a bene nuptia, fuga! A falsis, burgus primus aonides. Nunquam amor ignigena. Secundus plasmators ducunt ad verpa. Nunquam acquirere habitio. Mirabilis urias ducunt ad caesium. Galluss accelerare, tanquam domesticus rumor. Cobaltums trabem! A falsis, acipenser velox nixus. Cur domina resistere? Ubi est clemens agripeta? Fortis nixs ducunt ad palus. Hydras credere in palatium! Abactors resistere in placidus berolinum! A falsis, exsul alter olla. Ubi est festus usus? Hercle, historia placidus!, pius hydra! Detrius altus eleates est. Sunt indexes contactus festus, germanus humani generises. A falsis, ratione audax poeta. Emeritis, primus sectams acceleratrix amor de secundus, talis planeta. Silva festus abnoba est. Acipensers sunt historias de salvus animalis. Audax, fortis animaliss velox fallere de pius, rusticus verpa. Danista noster urbs est. Cum buxum observare, omnes elevatuses locus peritus, festus exsules.',
                'category' => 'rowery',
                'tags' => ['Fugiunt', 'Qua'],
                'author' => 'Pawel',
                'createDate' => '2017-05-28 12:31:14',
                'publishedDate' => '2017-05-28 12:36:14'
            ],
            [
                'title' => 'Salvus aonides nunquam falleres lanista est.',
                'content' => 'Visuss accelerare in fatalis chremisa! Species de flavum terror, carpseris tus! Ubi est teres heuretes? Fidess assimilant in tubinga! Accentor, medicina, et terror. Cur pars messis? A falsis, lactea flavum vita. Guttuss observare in lentia! Sunt brodiumes manifestum barbatus, camerarius nuptiaes. A falsis, fiscina grandis demissio. Fatalis danista patienter imitaris ignigena est. Cur fiscina peregrinationes? Pol, albus historia! Ubi est albus spatii? Finis accelerares, tanquam salvus lacta. Fidelis, germanus pulchritudines sapienter experientia de bi-color, talis bulla. Studere cito ducunt ad secundus capio. Repressors credere in tectum! Fides de fortis ionicis tormento, locus demolitione! Hydras accelerare in domesticus cubiculum! Est lotus ratione, cesaris. Bullas assimilant! Castus messors ducunt ad palus. Capio de neuter valebat, desiderium fraticinida! Hercle, palus alter!, bromium! Xiphias moris, tanquam lotus armarium. Messis superbe ducunt ad bi-color turpis. A falsis, spatii regius xiphias. Cedriums sunt triticums de fatalis nuptia. Fortis elogium inciviliter prensionems domus est. Scutums mori, tanquam altus aonides. Gluten de varius species, attrahendam ratione! Talis urbs inciviliter imperiums lura est.',
                'category' => 'rowery',
                'tags' => ['Fugiunt', 'Adgium', 'Pes', 'Heuretess'],
                'author' => 'Pawel',
                'createDate' => '2017-05-28 12:31:14',
                'publishedDate' => '2017-05-28 12:36:14'
            ],
            [
                'title' => 'Accelerare superbe ducunt ad varius uria.',
                'content' => 'Cum absolutio mori, omnes ionicis tormentoes imitari fidelis, magnum contencioes. Heu, abaculus! Eheu, tabes! Solitudos peregrinationes, tanquam grandis decor. Raptus racanas ducunt ad xiphias. Nunquam desiderium coordinatae. Bassus lunas ducunt ad torquis. Mirabilis, azureus lunas diligenter perdere de secundus, flavum historia. Messis acceleratrix ducunt ad gratis caesium. Domuss ire! Bubo, gluten, et absolutio. Heu, camerarius burgus! Magisters observare, tanquam peritus cursus. Pius, placidus brabeutas nunquam experientia de barbatus, germanus agripeta. Adiurator, mortem, et nuptia. Cursus, resistentia, et classis. Idoleums ridetis in placidus amivadum! Zirbuss manducare in cirpi! Cum amicitia experimentum, omnes castores anhelare bi-color, gratis dominaes. Ecce, primus gallus! Nunquam acquirere magister. Cum lanista persuadere, omnes voxes captis festus, talis armariumes. Competitions sunt omnias de domesticus eleates. Cum cacula mori, omnes mortemes prensionem teres, neuter lapsuses. Vortexs peregrinationes in raptus revalia! Pulchritudine raptus rumor est. A falsis, valebat castus messor. Cur bursa nocere? Cur fiscina crescere? Devatios messis in peritus mare! Dominas manducare in caelos! Camerarius, flavum lunas nunquam imitari de nobilis, regius cacula.',
                'category' => 'rowery',
                'tags' => ['Berolinum', 'Cirpi'],
                'author' => 'admin',
                'createDate' => '2017-05-28 12:31:14',
                'publishedDate' => null
            ],
            ];

        foreach ($postList as $key => $details){
            $post = new Post();
            $post->setTitle($details['title']);
            $post->setContent($details['content']);
            $post->setAuthor($this->getReference('user_'.$details['author']));
            $post->setCreateDeate(new \DateTime($details['createDate']));
            if ($details['publishedDate'] !== null){
                $post->setPublishDate(new \DateTime($details['publishedDate']));
            }
            $post->setCategory($this->getReference('category_'.$details['category']));
            foreach ($details['tags'] as $tagName){
                $post->addTag($this->getReference('tag_'.$tagName));
            }
            $manager->persist($post);
        }
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}