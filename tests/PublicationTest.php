<?php
namespace DsvSu\Daisy\Tests;

use DsvSu\Daisy\Publication;

class PublicationTest extends TestCase
{
    public function testFind()
    {
        $this->mockData('[{"id":9383,"title":"Automatic Construction of Domain-specific Dictionaries on Sparse Parallel Corpora in the Nordic languages","year":2008,"contributors":[{"id":21358,"firstName":"Hercules","lastName":"Dalianis","order":1,"function":null},{"id":21359,"firstName":"Sumithra","lastName":"Velupillai","order":0,"function":null}],"type":"paper","researchAreas":[{"id":20,"name":"Language Technology","name_en":"Language Technology","active":true}],"conference":"Workshop MMIES-2: Multi-source, Multilingual Information Extraction and Summarization, Held in conjunction with COLING-2008","publisher":"","location":""},{"id":9384,"title":"Diagnosing Diagnoses in Swedish Clinical Records","year":2008,"contributors":[{"id":21361,"firstName":"Sumithra","lastName":"Velupillai","order":0,"function":null},{"id":21362,"firstName":"Hercules","lastName":"Dalianis","order":1,"function":null},{"id":21363,"firstName":"Martin","lastName":"Duneld","order":2,"function":null}],"type":"paper","researchAreas":[{"id":12,"name":"Data and text mining","name_en":"Data and text mining","active":true},{"id":17,"name":"Healthcare Informatics","name_en":"Healthcare Informatics","active":true},{"id":20,"name":"Language Technology","name_en":"Language Technology","active":true}],"conference":"Proceedings of The First Conference on Text and Data Mining of Clinical Documents","publisher":"","location":"Turku, Louhi\'08, September 3-4 2008, pp. 110-112"},{"id":9426,"title":"Automatic Dictionary Construction and Identification of Parallel Text Pairs","year":2008,"contributors":[{"id":21477,"firstName":"Sumithra","lastName":"Velupillai","order":0,"function":null},{"id":21478,"firstName":"Martin","lastName":"Duneld","order":1,"function":null},{"id":21479,"firstName":"Hercules","lastName":"Dalianis","order":2,"function":null}],"type":"paper","researchAreas":[{"id":12,"name":"Data and text mining","name_en":"Data and text mining","active":true},{"id":20,"name":"Language Technology","name_en":"Language Technology","active":true}],"conference":"Proceedings of the International Symposium on Using Corpora in Contrastive and Translation Studies (UCCTS)","publisher":"","location":""}]');

        $pubs = Publication::find(
            [
                'lastName' => 'Dalianis',
                'from' => 2005,
                'until' => 2008
            ]
        );

        $this->assertEquals(3, count($pubs));

        $req = $this->getRequest();
        $this->assertEquals('/rest/publication', $req->getUri()->getPath());

        return $pubs;
    }

    /** @depends testFind */
    public function testGetTitle($pubs)
    {
        $this->assertEquals(
            'Automatic Construction of Domain-specific Dictionaries on Sparse Parallel Corpora in the Nordic languages',
            $pubs[0]->getTitle()
        );
        $this->assertEquals(
            'Diagnosing Diagnoses in Swedish Clinical Records',
            $pubs[1]->getTitle()
        );
    }

    /** @depends testFind */
    public function testGetYear($pubs)
    {
        $this->assertEquals(2008, $pubs[0]->getYear());
    }
    
    /** @depends testFind */
    public function testGetConference($pubs)
    {
        $this->assertEquals(
            'Workshop MMIES-2: Multi-source, Multilingual Information Extraction and Summarization, Held in conjunction with COLING-2008',
            $pubs[0]->getConference()
        );
    }

    /** @depends testFind */
    public function testGetType($pubs)
    {
        $this->assertInstanceOf('DsvSu\Daisy\PublicationType',
                                $pubs[0]->getType());
        $this->assertEquals('paper', $pubs[0]->getType()->getIdentifier());
        $this->assertEquals('Conference paper', $pubs[0]->getType()->getName());
    }

    /** @depends testFind */
    public function testGetId($pubs)
    {
        $this->assertEquals(9383, $pubs[0]->getId());
    }

    /** @depends testFind */
    public function testGetContributors($pubs)
    {
        $cs = $pubs[0]->getContributors();

        $this->assertEquals('Sumithra', $cs[0]->getFirstName());
        $this->assertEquals('Velupillai', $cs[0]->getLastName());

        $this->assertEquals('Hercules', $cs[1]->getFirstName());
        $this->assertEquals('Dalianis', $cs[1]->getLastName());
    }    
}
