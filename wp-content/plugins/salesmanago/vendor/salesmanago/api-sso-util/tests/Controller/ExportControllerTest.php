<?php


namespace Tests\Controller;

use Faker;
use PHPUnit\Framework\TestCase;
use SALESmanago\Controller\LoginController;
use SALESmanago\Entity\User;
use SALESmanago\Controller\ExportController;
use SALESmanago\Entity\Event\Event;
use SALESmanago\Entity\Configuration;
use SALESmanago\Model\Collections\ContactsCollection;
use SALESmanago\Model\Collections\EventsCollection;
use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Exception\Exception;

class ExportControllerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testExportEventsSuccess()
    {
        $faker = Faker\Factory::create();
        $user = new User();
        $eventCollection = new EventsCollection();

        $user
            ->setEmail('semowet930@boldhut.com')
            ->setPass('#Salesmanago123');

        $loginController = new LoginController(Configuration::getInstance());
        $loginController->login($user);//this one for property configuration create

        for ($i=0; $i<=100; $i++) {
            $event = new Event();
            $eventCollection->addItem(
                $event
                    ->setEmail($faker->email)
                    ->setContactExtEventType(Event::EVENT_TYPE_PURCHASE)
                    ->setProducts($faker->uuid)
                    ->setDescription($faker->text(200))
                    ->setDate($faker->time())
                    ->setExternalId($faker->uuid)
                    ->setLocation($faker->sha1)
                    ->setValue($faker->randomNumber())
            );
        }

        $exportController = new ExportController(Configuration::getInstance());
        $Response = $exportController->export($eventCollection);
        $this->assertEquals(true, $Response->getStatus());
    }

    /**
     * @throws Exception
     */
    public function testExportContactsSuccess()
    {
            $faker = Faker\Factory::create();
            $conf = Configuration::getInstance();
            $user = new User();
            $contactsCollection = new ContactsCollection();

            $user
                ->setEmail('semowet930@boldhut.com')
                ->setPass('#Salesmanago123');

            $loginController = new LoginController($conf);
            $loginController->login($user);//this one for property configuration create

            for ($i = 0; $i <= 100; $i++) {
                $Contact = new Contact();
                $contactsCollection->addItem(
                    $Contact
                        ->setName($faker->name)
                        ->setEmail($faker->email)
                        ->setFax($faker->phoneNumber)
                        ->setPhone($faker->phoneNumber)
                        ->setCompany($faker->company)
                        ->setExternalId($faker->uuid)
                        ->setState($faker->randomElement(['CUSTOMER', 'PROSPECT', 'PARTNER', 'OTHER', 'UNKNOWN']))
                        ->setOptions(
                            $Contact->getOptions()
                                ->setTags($faker->words($nb = 3, $asText = false))
                                ->setIsSubscribed($faker->boolean)
                                ->setCreatedOn($faker->unixTime($max = 'now'))
                        )
                );
            }

            $exportController = new ExportController($conf);
            $Response = $exportController->export($contactsCollection);

            $this->assertEquals(true, $Response->getStatus());
    }
}