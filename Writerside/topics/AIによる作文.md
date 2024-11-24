# 参考用: AIによる作文

## misc
### generated-by
JetBrains/AI Assistant (openai-gpt-4o)

## prompts
PHPStanユーザーです。 `@template-covariant` と `@template-contravariant`について、概要や利点を説明し、その後に具体的なコードを交えて解決される問題について解説してください

## answer
AI Assistant
それでは、PHPStanにおける`@template-covariant`と`@template-contravariant`について説明し、具体的なコード例を交えて解説します。


### 概要
#### @template-covariant
コバリアンス（Covariance）とは、型パラメータがある場所で、サブクラスも使用可能である性質を指します。コバリアンスを使用すると、特定の型パラメータを持つクラスやインターフェースが、その型パラメータのサブタイプも許容することができます。
#### @template-contravariant
コントラバリアンス（Contravariance）とは、型パラメータがある場所で、スーパークラスも使用可能である性質を指します。コントラバリアンスを使用すると、特定の型パラメータを持つクラスやインターフェースが、その型パラメータのスーパークラスも許容することができます。

### 利点
- **柔軟性の向上:** 型パラメータに対する柔軟性が向上し、より具体的な型やより一般的な型を許容することができます。
- **コードの再利用性:** コードの再利用性が向上し、共通コードを異なる型パラメータのシナリオで利用することが容易になります。
- **型安全性の向上:** 型システムが強化され、不正な型の利用を防ぐことができます。

### コード例と問題解決

#### コバリアンスの例
まず、コバリアンスの簡単な例を示します。

```php
<?php

/**
 * @template-covariant T
 */
class Box {
    /**
     * @var T
     */
    private $content;

    /**
     * @param T $content
     */
    public function __construct($content) {
        $this->content = $content;
    }

    /**
     * @return T
     */
    public function getContent() {
        return $this->content;
    }
}

class Fruit {}
class Apple extends Fruit {}

function receiveFruitBox(Box $box): void {
    $fruit = $box->getContent();
    // Do something with $fruit
}

$appleBox = new Box(new Apple());
receiveFruitBox($appleBox); // Apple is a subtype of Fruit, so this works due to covariance
```

ここで、`Box<Apple>` 型のオブジェクトを `Box<Fruit>` 型の引数として渡すことができます。コバリアンスがなければ、これは型エラーになります。
#### コントラバリアンスの例
次に、コントラバリアンスの簡単な例を示します。

```php
<?php

/**
 * @template-contravariant T
 */
interface Comparator {
    /**
     * @param T $a
     * @param T $b
     * @return int
     */
    public function compare($a, $b): int;
}

class Person {
    public $name;

    public function __construct(string $name) {
        $this->name = $name;
    }
}

class Employee extends Person {
    public $salary;

    public function __construct(string $name, int $salary) {
        parent::__construct($name);
        $this->salary = $salary;
    }
}

class PersonComparator implements Comparator {
    public function compare($a, $b): int {
        return strcmp($a->name, $b->name);
    }
}

function sortPeople(array $people, Comparator $comparator): array {
    usort($people, [$comparator, 'compare']);
    return $people;
}

$personComparator = new PersonComparator();
$employees = [new Employee('Alice', 50000), new Employee('Bob', 60000)];

sortPeople($employees, $personComparator); // PersonComparator works for Employee due to contravariance
```

ここでは、`Comparator<Person>` 型のオブジェクトを `Comparator<Employee>` 型の引数として渡すことができます。コントラバリアンスがなければ、これは型エラーになります。

### 結論
`@template-covariant` と `@template-contravariant` を使用することで、ジェネリクスにおける型の柔軟性が向上し、より多様なシcenariosに対応可能になります。結果として、型安全性を維持しながら、コードの再利用性や可読性が高まります。
