<?php

declare(strict_types=1);

namespace Specification\Akeneo\Pim\Enrichment\Component\Product\Webhook;

use Akeneo\Pim\Enrichment\Component\Product\Message\ProductCreated;
use Akeneo\Pim\Enrichment\Component\Product\Message\ProductRemoved;
use Akeneo\Pim\Enrichment\Component\Product\Webhook\ProductRemovedEventDataBuilder;
use Akeneo\Platform\Component\EventQueue\Author;
use Akeneo\Platform\Component\Webhook\EventDataBuilderInterface;
use Akeneo\Platform\Component\Webhook\EventDataCollection;
use Akeneo\UserManagement\Component\Model\UserInterface;
use PhpSpec\ObjectBehavior;
use PHPUnit\Framework\Assert;

class ProductRemovedEventDataBuilderSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith();
    }

    public function it_is_initializable()
    {
        $this->shouldBeAnInstanceOf(ProductRemovedEventDataBuilder::class);
        $this->shouldImplement(EventDataBuilderInterface::class);
    }

    public function it_supports_product_removed_event(): void
    {
        $author = Author::fromNameAndType('julia', Author::TYPE_UI);

        $this->supports(
            new ProductRemoved($author, ['identifier' => 'product_identifier', 'category_codes' => []]),
        )->shouldReturn(true);
    }

    public function it_does_not_supports_other_business_event(): void
    {
        $author = Author::fromNameAndType('julia', Author::TYPE_UI);

        $this->supports(new ProductCreated($author, ['identifier' => 'product_identifier']))->shouldReturn(false);
    }

    public function it_builds_product_removed_event(UserInterface $user): void
    {
        $author = Author::fromNameAndType('julia', Author::TYPE_UI);
        $event = new ProductRemoved($author, ['identifier' => 'product_identifier', 'category_codes' => []]);

        $expectedCollection = new EventDataCollection();
        $expectedCollection->setEventData($event, ['resource' => ['identifier' => 'product_identifier']]);

        $collection = $this->build($event, $user)->getWrappedObject();

        Assert::assertEquals($expectedCollection, $collection);
    }

    public function it_does_not_build_other_business_event(UserInterface $user): void
    {
        $author = Author::fromNameAndType('julia', Author::TYPE_UI);

        $this->shouldThrow(new \InvalidArgumentException())->during('build', [
            new ProductCreated($author, ['identifier' => '1']),
            $user,
        ]);
    }
}