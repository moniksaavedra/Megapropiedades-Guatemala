<?php


namespace Tests\Feature\Controller;

use Faker;
use Tests\Feature\TestCaseUnit;
use ReflectionException;

use SALESmanago\Controller\ExportController;
use SALESmanago\Entity\Event\Event;
use SALESmanago\Model\Collections\ContactsCollection;
use SALESmanago\Model\Collections\EventsCollection;
use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Exception\Exception;
use SALESmanago\Entity\cUrlClientConfiguration;


class ExportControllerTest extends TestCaseUnit
{
    /**
     * @throws Exception
     */
    public function testExportEventsSuccess()
    {
        $conf = $this->initConf();
        $conf->setRequestClientConf(
            new cUrlClientConfiguration(
                [
                    cUrlClientConfiguration::HOST => $conf->getEndpoint()
                ]
            )
        );

        $faker = Faker\Factory::create();
        $eventCollection = new EventsCollection();

        for ($i=0; $i<=100; $i++) {
            $event = new Event();
            $eventCollection->addItem(
                $event
                    ->setEmail($faker->email)
                    ->setContactExtEventType(Event::EVENT_TYPE_PURCHASE)
                    ->setProducts($faker->uuid)
                    ->setDescription($faker->text)
                    ->setDate(time())
                    ->setExternalId($faker->uuid)
                    ->setLocation($faker->sha1)
                    ->setValue($faker->randomNumber())
            );
        }

        $exportController = new ExportController($conf);
        $Response = $exportController->export($eventCollection);
        $this->assertEquals(true, $Response->getStatus());
    }

    /**
     * @throws Exception
     */
    public function testExportContactsSuccess()
    {
            $contactsCollection = $this->generateContactsCollection();
            $exportController   = new ExportController($this->initConf());
            $Response           = $exportController->export($contactsCollection);

            $this->assertEquals(true, $Response->getStatus());
    }

    /**
     * @throws Exception
     * @throws ReflectionException
     */
    public function testCheckAndFilterContactsCollectionSuccess()
    {
        $faker = Faker\Factory::create();
        $conf = $this->initConf();

        $numberOfGeneratedEmailDomains = $faker->numberBetween(1, 50);
        $generatedEmailDomains = $this->generateEmailDomains($numberOfGeneratedEmailDomains);
        $conf->setIgnoredDomains($generatedEmailDomains);

        $numberOfAllContact = $faker->numberBetween(1, 200);
        $numberOfContactsWithIgnoredDomains = $faker->numberBetween(1, $numberOfAllContact);

        $contactsCollection = $this->generateContactsCollection(
            $numberOfAllContact,
            $generatedEmailDomains,
            $numberOfContactsWithIgnoredDomains
        );

        $exportController = new ExportController($conf);

        $method = $this->getMethod('checkAndFilterContactsCollection', $exportController);
        $filteredCollection = $method->invokeArgs($exportController, [$contactsCollection]);

        $expectingCollectionSize = $numberOfAllContact - $numberOfContactsWithIgnoredDomains;

        $this->assertEquals($filteredCollection->count(), $expectingCollectionSize);
    }

    /**
     * Generates email domains
     *
     * @param int $numberOfUniqueDomainNames
     * @return array
     */
    protected function generateEmailDomains($numberOfUniqueDomainNames = 1)
    {
        $faker = Faker\Factory::create();
        $domains = [];

        while ($numberOfUniqueDomainNames) {
            array_push($domains, $faker->word . '.' . $faker->word);
            $numberOfUniqueDomainNames--;
        }

        return $domains;
    }

    /**
     * Generates contacts collection
     *
     * @param int $totalNrOfItems
     * @param array $specialEmailDomains - array of pre defined email domains
     * @param null $nrOfContactWithSpecialEmailDomains - can't be greater than $nrOfItems
     * @return ContactsCollection
     * @throws Exception
     */
    protected function generateContactsCollection(
        $totalNrOfItems = 100,
        $specialEmailDomains = [],
        $nrOfContactWithSpecialEmailDomains = null
    ) {
        $faker = Faker\Factory::create();
        $contactsCollection = new ContactsCollection();

        $totalNrOfItems = ($nrOfContactWithSpecialEmailDomains != null)
            ? $totalNrOfItems - $nrOfContactWithSpecialEmailDomains
            : $totalNrOfItems;

        for ($i = 0; $i < $totalNrOfItems; $i++) {
            $Contact = $this->generateContact();
            $contactsCollection->addItem($Contact);
        }

        if (empty($specialEmailDomains)) {
            return $contactsCollection;
        }

        for ($i = 0; $i < $nrOfContactWithSpecialEmailDomains; $i++) {
            $contactEmail = $faker->word . '@' . $specialEmailDomains[array_rand($specialEmailDomains)];
            $Contact = $this->generateContact(
                null,
                $contactEmail
            );
            $contactsCollection->addItem($Contact);
        }

        return $contactsCollection;
    }

    /**
     * Generate contact
     *
     * @param null $name
     * @param null $email
     * @param null $faxNumber
     * @param null $phoneNumber
     * @param null $company
     * @param null $externalId
     * @param null $state
     * @param array $tags
     * @param null|bool $isSubscribed
     * @param null|int $createdOn
     * @return Contact
     */
    protected function generateContact(
        $name         = null,
        $email        = null,
        $faxNumber    = null,
        $phoneNumber  = null,
        $company      = null,
        $externalId   = null,
        $state        = null,
        $tags         = [],
        $isSubscribed = null,
        $createdOn    = null
    ) {
        $faker = Faker\Factory::create();
        $Contact = new Contact();

        $Contact
            ->setName($name ?? $faker->name)
            ->setEmail($email ?? $faker->email)
            ->setFax($faxNumber ?? $faker->phoneNumber)
            ->setPhone($phoneNumber ?? $faker->phoneNumber)
            ->setCompany($company ?? $faker->company)
            ->setExternalId($externalId ?? $faker->uuid)
            ->setState($state ?? $faker->randomElement(['CUSTOMER', 'PROSPECT', 'PARTNER', 'OTHER', 'UNKNOWN']))
            ->setOptions(
                $Contact->getOptions()
                    ->setTags(empty($tags) ? $faker->words($nb = 3, $asText = false) : $tags)
                    ->setIsSubscribed($isSubscribed ?? $faker->boolean)
                    ->setCreatedOn($createdOn ?? $faker->unixTime($max = 'now'))
            );

        return $Contact;
    }
}
