<?php

namespace App\Tests\Repository;

use App\Entity\Faq;
use App\Entity\Faqpage;
use App\Entity\FaqSubject;
use App\Entity\Subject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FaqRepositoryTest extends KernelTestCase 
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp() : void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * Tests database connection.
     * Exits if fails to connect to the database.
     *
     * @return void
     */
    public function test_DatabaseConnection()
    {
        $this->expectNotToPerformAssertions();

        try {
            $this->entityManager->getConnection()->connect();
        } catch (\Exception $e) {
            // failed to connect
            print("\nFailed to connect to database.");
            print("\nError:\n");
            print($e->getMessage());
            print("\n");
            exit;
        }

        print("\nConnected to database successfully!\n");
    }

    /**
     * Asserts whether GetAllFaqs(<void>) returns an array.
     *
     * @return void
     */
    public function test_GetAllFaqs_ReturnsAnArray()
    {
        // Call getAllFaqs()
        $faqs = $this->entityManager
            ->getRepository(Faq::class)
            ->getAllFaqs();

        // Check whether faqs is an array
        $this->assertIsArray($faqs);
    }

    /**
     * Asserts whether GetAllFaqs(<void>) contains only objects of type
     * "\App\Entity\Faq" in the returned array.
     *
     * @return void
     */
    public function test_GetAllFaqs_ContainsOnlyFaq()
    {
        // Call getAllFaqs()
        $faqs = $this->entityManager
            ->getRepository(Faq::class)
            ->getAllFaqs();

        // Check whether faqs only contain values of the Faq type
        $this->assertContainsOnly("\App\Entity\Faq", $faqs);
    }

    /**
     * Asserts whether a valid faq id exists in the array of Faq entities 
     * returned in GetAllFaqs(<void>).
     *
     * @return void
     */
    public function test_GetAllFaqs_FaqIdExists()
    {
        // Call getAllFaqs()
        $faqs = $this->entityManager
            ->getRepository(Faq::class)
            ->getAllFaqs();

        $faq_id = 43; // populate with faq_id from database

        $this->assertArrayHasKey($faq_id, $faqs);
    }

    /**
     * Asserts that an invalid faq id is not present in the array of Faq entities 
     * returned in GetAllFaqs(<void>).
     * @return void
     */
    public function test_GetAllFaqs_FaqIdDoesNotExist()
    {
        // Call getAllFaqs()
        $faqs = $this->entityManager
            ->getRepository(Faq::class)
            ->getAllFaqs();

        $faq_id = 0; // populate with faq_id NOT in the database

        $this->assertArrayNotHasKey($faq_id, $faqs);
    }

    /**
     * Asserts whether retrieving a question using faq_id from GetAllFaqs(<void>) will
     * return an accurate result.
     *
     * @return void
     */
    public function test_GetAllFaqs_HasQuestion()
    {
        // Call getAllFaqs()
        $faqs = $this->entityManager
            ->getRepository(Faq::class)
            ->getAllFaqs();

        $faq_id = 43; // populate with faq_id from database
        $question = "How will I know my request is ready for pick up?"; // question from faq_id in database

        $faq_question = $faqs[$faq_id]->getQuestion();
        
        $this->assertEquals(trim($question), trim($faq_question));
    }

    /**
     * Asserts whether GetFaqsBySubject(<Subject>) returns an array.
     *
     * @return void
     */
    public function test_GetFaqsBySubject_ReturnsAnArray()
    {
        // Retrieve Subject
        $subject = $this->entityManager
        ->getRepository(Subject::class)
        ->findOneBy(['subject' => 'Book a Library Seat', 'active' => 1]);

        // Call getFaqsBySubject(<Subject>)
        $faqs = $this->entityManager
        ->getRepository(Faq::class)
        ->getFaqsBySubject($subject);

        $this->assertIsArray($faqs);
    }

    /**
     * Asserts whether GetFaqsBySubject(<Subject>) contains only objects of type
     * "\App\Entity\Faq" in the returned array.
     *
     * @return void
     */
    public function test_GetFaqsBySubject_ContainsOnlyFaq()
    {
        // Retrieve Subject
        $subject = $this->entityManager
        ->getRepository(Subject::class)
        ->findOneBy(['subject' => 'Book a Library Seat', 'active' => 1]);

        // Call getFaqsBySubject(<Subject>)
        $faqs = $this->entityManager
        ->getRepository(Faq::class)
        ->getFaqsBySubject($subject);

        $this->assertContainsOnly("\App\Entity\Faq", $faqs);
    }

    /**
     * Asserts whether a valid faq id exists in the array of Faq entities 
     * returned in GetFaqsBySubject(<Subject>).
     *
     * @return void
     */
    public function test_GetFaqsBySubject_FaqIdExists()
    {
        // Retrieve Subject
        $subject = $this->entityManager
        ->getRepository(Subject::class)
        ->findOneBy(['subject' => 'Book a Library Seat', 'active' => 1]);

        // Call getFaqsBySubject(<Subject>)
        $faqs = $this->entityManager
        ->getRepository(Faq::class)
        ->getFaqsBySubject($subject);

        $faq_id = 107; // populate with faq_id containing "Book a Library Seat" subject

        $this->assertArrayHasKey($faq_id, $faqs);
    }

    /**
     * Asserts that an invalid faq id is not present in the array of Faq entities 
     * returned in GetFaqsBySubject(<Subject>).
     *
     * @return void
     */
    public function test_GetFaqsBySubject_FaqIdDoesNotExist()
    {
        // Retrieve Subject
        $subject = $this->entityManager
        ->getRepository(Subject::class)
        ->findOneBy(['subject' => 'Book a Library Seat', 'active' => 1]);

        // Call getFaqsBySubject(<Subject>)
        $faqs = $this->entityManager
        ->getRepository(Faq::class)
        ->getFaqsBySubject($subject);

        $faq_id = 10; // populate with faq_id that does NOT contain "Book a Library Seat" subject
        
        $this->assertArrayNotHasKey($faq_id, $faqs);
    }

    /**
     * Asserts whether retrieving a question using faq_id from GetFaqsBySubject(<Subject>) will
     * return an accurate result.
     *
     * @return void
     */
    public function test_GetFaqsBySubject_HasQuestion()
    {
        // Retrieve Subject
        $subject = $this->entityManager
        ->getRepository(Subject::class)
        ->findOneBy(['subject' => 'Book a Library Seat', 'active' => 1]);

        // Call getFaqsBySubject(<Subject>)
        $faqs = $this->entityManager
        ->getRepository(Faq::class)
        ->getFaqsBySubject($subject);

        $faq_id = 107; // populate with faq_id that contains "Book a Library Seat" subject
        $question = "Are the distinctive collections (Special Collections, Cuban Heritage Collection, University Archives) open for visitors?"; // question from faq_id in database

        $faq_question = $faqs[$faq_id]->getQuestion();
        
        $this->assertEquals(trim($question), trim($faq_question));
    }

    /**
     * Asserts whether GetFaqsByCollection(<Collection>) returns an array.
     *
     * @return void
     */
    public function test_GetFaqsByCollection_ReturnsAnArray()
    {
        // Retrieve Faqpage/Collection
        $faqpageId = 8;
        $collection = $this->entityManager->find(Faqpage::class, $faqpageId);

        // Call getFaqsByCollection(<Faqpage>)
        $faqs = $this->entityManager
        ->getRepository(Faq::class)
        ->getFaqsByCollection($collection);

        $this->assertIsArray($faqs);
    }

    /**
     * Asserts whether GetFaqsByCollection(<Collection>) contains only objects of type
     * "\App\Entity\Faq" in the returned array.
     *
     * @return void
     */
    public function test_GetFaqsByCollection_ContainsOnlyFaq()
    {
        // Retrieve Faqpage/Collection
        $faqpageId = 8; // "Interlibrary Loan" Page ID
        $collection = $this->entityManager->find(Faqpage::class, $faqpageId);

        // Call getFaqsByCollection(<Faqpage>)
        $faqs = $this->entityManager
        ->getRepository(Faq::class)
        ->getFaqsByCollection($collection);

        $this->assertContainsOnly("\App\Entity\Faq", $faqs);
    }

    /**
     * Asserts whether a valid faq id exists in the array of Faq entities 
     * returned in GetFaqsByCollection(<Collection>).
     *
     * @return void
     */
    public function test_GetFaqsByCollection_FaqIdExists()
    {
        // Retrieve Faqpage/Collection
        $faqpageId = 8; // "Interlibrary Loan" Page ID
        $collection = $this->entityManager->find(Faqpage::class, $faqpageId);

        // Call getFaqsByCollection(<Faqpage>)
        $faqs = $this->entityManager
        ->getRepository(Faq::class)
        ->getFaqsByCollection($collection);

        $faq_id = 73; // populate with faq_id that is apart of "Interlibrary Loan" faqpage

        $this->assertArrayHasKey($faq_id, $faqs);
    }

    /**
     * Asserts that an invalid faq id is not present in the array of Faq entities 
     * returned in GetFaqsByCollection(<Collection>).
     *
     * @return void
     */
    public function test_GetFaqsByCollection_FaqIdDoesNotExist()
    {
        // Retrieve Faqpage/Collection
        $faqpageId = 8; // "Interlibrary Loan" Page ID
        $collection = $this->entityManager->find(Faqpage::class, $faqpageId);

        // Call getFaqsByCollection(<Faqpage>)
        $faqs = $this->entityManager
        ->getRepository(Faq::class)
        ->getFaqsByCollection($collection);

        $faq_id = 11; // populate with faq_id that is NOT apart of "Interlibrary Loan" faqpage
        
        $this->assertArrayNotHasKey($faq_id, $faqs);
    }

    /**
     * Asserts whether retrieving a question using faq_id from GetFaqsByCollection(<Collection>) will
     * return an accurate result.
     *
     * @return void
     */
    public function test_GetFaqsByCollection_HasQuestion()
    {
        // Retrieve Faqpage/Collection
        $faqpageId = 8; // "Interlibrary Loan" Page ID
        $collection = $this->entityManager->find(Faqpage::class, $faqpageId);

        // Call getFaqsByCollection(<Faqpage>)
        $faqs = $this->entityManager
        ->getRepository(Faq::class)
        ->getFaqsByCollection($collection);

        $faq_id = 73; // populate with faq_id that is apart of "Interlibrary Loan" faqpage
        $question = "What is electronic delivery?"; // question from faq_id in database

        $faq_question = $faqs[$faq_id]->getQuestion();
        
        $this->assertEquals(trim($question), trim($faq_question));
    }

    /**
     * Asserts whether GetAllFaqsBySubject(<void>) returns an array.
     *
     * @return void
     */
    public function test_GetAllFaqsBySubject_ReturnsAnArray()
    {
        // Call getFaqsBySubjects(<void>)
        $faqs = $this->entityManager
        ->getRepository(Faq::class)
        ->getAllFaqsBySubject();

        $this->assertIsArray($faqs);
    }

        /**
     * Asserts whether GetAllFaqsByCollection(<void>) returns an array.
     *
     * @return void
     */
    public function test_GetAllFaqsByCollection_ReturnsAnArray()
    {
        // Call getFaqsBySubjects(<void>)
        $faqs = $this->entityManager
        ->getRepository(Faq::class)
        ->getAllFaqsByCollection();

        $this->assertIsArray($faqs);
    }

    protected function tearDown() : void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }


}