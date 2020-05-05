---
title: 'GildedRose Factoring Kata '
date: 2020-04-05 19:39:24
tags:
- php
- refactoring
- kata
img: /medias/featureimages/working_effectively_with_legacy_code.jpeg
---

這篇逐步介紹自己練習重構與 TDD 開發 Legacy Code Kata 的解題思路，題目是社群 *Domain Driven Design(DDD Taiwan)* 所舉辦的讀書會 Legacy Code 讀書會 3rd (Ch 8, 9) 所分享的重構練習題目。
 
 **GildedRose Refactoring Kata**

另外感謝這次的講者 *Blues* 與社群用心的準備，自己蠻喜歡這次的題目，這次重構時決定嘗試做一次筆記，對自己還是很有收穫的，未來如果自己舉辦公司讀書會的話，將本題作為練習題也是非常適合。

* [GildedRose Refactoring Kata 題目程式碼來源](https://github.com/emilybache/GildedRose-Refactoring-Kata)
* [講者 Blues 的題目介紹](https://hackmd.io/Lx84B9vHQ5irqcVhb7ebPQ?both)
* [本文的程式碼 Repository](https://github.com/mpp21x/PHP-GildedRose-Refactoring-Kata)

##### legacy code 重構前後
![重構前後比較](compare_refactoring.png)

### 開發流程

- 按照預期完成的測試案例項目撰寫程式碼
- 重構 legacy code
- TDD 新增新的需求
- (補充)修正測試案例五漏洞

## 題目描述

嗨，歡迎來到鍍金玫瑰團隊。如您所知，我們是一家小旅館，位於一線城市的黃金地段，旅館老闆是艾里森(Allison)。我們只銷售高級商品。不過每件商品都有一個銷售剩餘天數。

隨著時間的推移，商品的品質會不斷下降。

我們擁有一個可以更新庫存訊息的系統。有位名叫 River 的開發人員開發了這個系統，之後就離開了。

你的任務是將新功能添加到該系統中，以便我們可以開始銷售新種類的商品。

### 題目原始需求

1. 所有商品(item)都有一個銷售剩餘天數(SellIn)值，表示該商品必須在該值所代表的天數內銷售出去。
2. 所有商品都有一個品質(Quality)值，表明該商品當前的品質。
3. 每天結束時，系統會降低每個商品的上述兩個值。
4. 一但商品過了銷售剩餘天數之後還沒未賣出，那麼其每日品質下降的速度就會加倍。
5. 商品的品質不能為負數。
6. 陳年乾酪(Aged Brie)的品質值隨著時間的推移，不減反增。
7. 商品的品質的上限為50。
8. 魔法錘(Sulfuras)是一個傳奇商品，其銷售剩餘天數和品質都不會變化。
9. 劇院後台通行證(Backstage passes)，就像陳年乾酪一樣，其品質會隨著銷售剩餘天數的減少而提高-當離演出開始不到10天時，品質每日提高2；當不到5天時，品質值每日提高3，當演出結束後，品質歸0。
10. (新需求)：魔法(Conjured)商品每日品質下降速度是正常商品的2倍

在擁有系統的需求之後，首先要做的便是將消化此需求，並轉為實務開發的測試案例，有了測試案例之後，即可依照測試案例開始來撰寫測試的程式碼。

### 預期完成的測試案例項目

1. 原需求 3：確保一般的商品在每天結束後 ( 也就是 `updateQuality` 的 method 呼叫之後 )，系統會降低 `sellIn` 和 `quality` 兩種屬性，降低的程度由原程式碼所決定。
2. 原需求 5：確保 `item` 的 `quality` 從不為負數
3. 原需求 4 ：確保 `item` 的`sellIn` 為零時，若 `quality` 還不等於零，其下降速度是原本的兩倍
4. 原需求 6：商品 Aged Brie 的物件 `item`，經過 `updateQuality` 呼叫之後，其 `quality` 不減反增，即使在 `sellIn` 歸零也是
5. 原需求 7 ： 確保 `item` 的 `quality` 從來不超過 50
6. 原需求 8：商品 Sulfuras 的物件 `item` ，其 `sellIn` 和 `quality` 初始化後，永久不變
7. 原需求 9：商品 Backstage passes 的物件 `item` 須滿足下列條件：
    1. 當 `sellIn` 為 9 到 5 的時候，`updateQuality` 一次提升 `quality` 2
    2. 當 `sellIn` 為 4 到 1 的時候，`updateQuality` 一次提升 `quality` 3
    3. 當 `sellIn` 為 0，`quality` 將直接歸零
8. 原需求 10 ：新增一個 Conjured 商品，`updateQuality` 所下降的值為正常商品的兩倍

這裡想補充幾點：
* 上述的需求中，如果有較描述模糊的地帶，這裏傾向先自己推理與腦補，實務上最好還是跟 PM 或客戶確認需求，避免雙方預期需求上的認知差異。
* 程式碼所使用的商品名稱來源是：`fixtures/texttest_fixture.php` 以及 `src/GildedRose`，例如：
    * 商品名稱：`Backstage`，在程式碼實際上名稱為 `Backstage passes to a TAFKAL80ETC concert`
* 撰寫測試案例的流程，盡可能地將有關聯性的測試案例安排在一起先行撰寫，使後續撰寫測試程式碼時，也可避免出現不必要的修改。


## 按照預期完成的測試案例項目撰寫程式碼

### 測試案例一

- 需求：確保一般的商品在每天結束後 ( 也就是 `updateQuality` 的 method 呼叫之後 )，系統會降低 `sellIn` 和 `quality` 兩種屬性，降低的程度由原程式碼所決定。

既然我們已經擁有明確的測試案例以及需求了，接下來便是按照需求逐步完成測試，開啟預設的測試檔 `GildedRoseTest`，會看到下面這個 method：

```php
public function testFoo()
{
    $items = [new Item("foo", 0, 0)];
    $gildedRose = new GildedRose($items);
    $gildedRose->updateQuality();
    $this->assertEquals("fixme", $items[0]->name);
}
```


接下來先進行基本的撰寫測試與重構，這裡開始描述實際撰寫測試的流程：

##### 實際撰寫測試的流程
- 修改 `testFoo` method 名稱為 `testNormalProductUpdateQuality` 方便理解這個測試案例的情境。
- 執行測試，發現紅燈： `Failed asserting that two strings are identical`
- 修改 `item` 的 `quality` 以及 `sellIn` 為 1 ，滿足測試。
- 修正第一個 `assertEquals` 中第一個參數的 `fixme` 為 `foo`，並且調整 `assertEquals` 為 `assertSame` ，確保較嚴謹的判斷。
- 新增一個 `assertEquals` 來測試 `quality` 是否如預期下降 1， 為什麼是 1 呢？這個數字是從查看物件 `GildedRose` 裡面得知的，需求裡面沒有提到這一塊所以就沿用原設計。
- 新增一個 `assertEquals` 來測試 `sellIn` 是否如預期下降 1，原因如上
- 執行測試，綠燈。

最後完成如下：

```php
public function testNormalProductUpdateQuality()
{
    $item = new Item("foo", 1, 1);
    $items = [$item];
    $gildedRose = new GildedRose($items);
    $gildedRose->updateQuality();
    $this->assertSame("foo", $item->name);
    $this->assertSame(0, $item->sellIn);
    $this->assertSame(0, $item->quality);
}
```

再來便是重構測試程式碼的流程，首先重構 `GildedRoseTest`：

- extract `new Item("foo", 0, 0)` 到 property，確保 IDE 可以取得存取的變數來源是什麼型別。
- 重構使用到 `item` 物件的程式碼。
- extract 測試目標的 `item` 到 `GildedRoseTest` 上，以便滿足後續測試時需要將參數一一帶入至測試的 method
- extract 測試方法 `shouldBe` ，減少之後重複的程式碼
- 補足 PHPDoc 或是強型別 ，確保 IDE(PHPStorm) 知道每一個物件的型別為何。

最後完成如下：

```php

class GildedRoseTest extends TestCase
{
    /** @var Item */
    private $item;

    public function testNormalProductUpdateQuality()
    {
        $this->item = new Item("foo", 1, 1);
        $items = [$this->item];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();
        $this->assertSame("foo", $this->item->name);
        $this->shouldBe(0, 0);
    }

    protected function shouldBe(int $sellIn, int $quality): void
    {
        $this->assertSame($sellIn, $this->item->sellIn);
        $this->assertSame($quality, $this->item->quality);
    }
}
```

在重構完之後，再次執行測試，確認綠燈後便完成第一個測試案例，準備提交。

[git 測試案例一提交記錄](https://github.com/mpp21x/PHP-GildedRoseRefactoringKata/commit/c3b0b14aec30b414bea0cfc961e23b6198a961ae)

### 測試案例二

- 需求：確保 `item` 的 `quality` 從不為負數

##### 實際撰寫測試的流程

- 新增測試 method `testProductQualityOrSellInIsNeverBeLessThenZero`
- 複製 測試案例一 的程式碼，並將 `sellIn` 以及 `quality` 改為 0，預期這兩個屬性在 `updateQuality` 之後，仍然為 0
- 執行測試，發現紅燈： `Failed asserting that -1 is identical to 0`，這是 `quality` 的錯誤。
- 修正 `GildedRose` 物件的程式碼滿足測試，修改的程式碼如下：

![](test_case_2.png)

- 執行測試，綠燈，完成如下：

```php

public function testProductQualityOrSellInIsNeverBeLessThenZero()
{
    $this->item = new Item("foo", 0, 0);
    $items = [$this->item];
    $gildedRose = new GildedRose($items);
    $gildedRose->updateQuality();
    $this->shouldBe(0, 0);
}
```

開始重構測試程式碼

- 在 `GildedRose` 的屬性 `items` 新增 PHPDoc ，使 PHPStorm 可以讀取來源：

```php
/** @var Item[] */
private $items = [];
```    

- extract 建立 `GildedRose` 的那一段程式碼 extract 到一個新的 method 為 `updateQuality`，常用的程式碼以便之後重複使用：

```php

protected function updateQuality(): void
{
    $items = [$this->item];
    $gildedRose = new GildedRose($items);
    $gildedRose->updateQuality();
}
```

- extract 建立 `Item` 的邏輯下面這一個 method，一樣是為了使常用的程式碼能重複使用 ：

```php

protected function createItem(string $name, int $sellIn, int $quality)
{
    $this->item = new Item($name, $sellIn, $quality);
}
```

- 執行測試，綠燈，完成的程式碼如下：

```php

public function testNormalProductUpdateQuality()
{
    $this->createItem("foo", 1, 1);
    $this->updateQuality();
    $this->assertSame("foo", $this->item->name);
    $this->shouldBe(0, 0);
}

public function testProductQualityOrSellInIsNeverBeLessThenZero()
{
    $this->createItem("foo", 0, 0);
    $this->updateQuality();
    $this->shouldBe(0, 0);
}
```

[git 測試案例二提交記錄](https://github.com/mpp21x/PHP-GildedRoseRefactoringKata/commit/5df69be2967dbf713c3243f995ab65e57e202530)

### 測試案例三

- 需求：確保 `item` 的`sellIn` 為零時，若 `quality` 還不等於零，其下降速度是原本的兩倍

實際撰寫測試的流程

- 新增測試 method `testProductQualityDecreaseDoubleWhenSellInIsZero`
- 初始化商品的 `sellIn` 為 0 ，然後 `quality` 為 2
- 撰寫測試，預期在 `updateQuality` 之後， `quality` 為 0
- 執行測試，發現紅燈： `Failed asserting that 1 is identical to 0.`，看來原本的設計是沒有撰寫這一部分邏輯
- 補足需求：`sellIn` 為零時，若 `quality` 還不等於零，其下降速度是原本的兩倍，修改 `GildedRose` 程式碼如下：

![](test_case_3.png)

- 執行測試，綠燈

```php

public function testProductQualityDecreaseDoubleWhenSellInIsZero()
{
    $this->createItem("foo", 0, 2);
    $this->updateQuality();
    $this->shouldBe(0, 0);
}
```

[git 測試案例三提交記錄](https://github.com/mpp21x/PHP-GildedRoseRefactoringKata/commit/2ce47df88a4507855a287856deac3f16086ff979)

### 測試案例四

- 需求：商品 Aged Brie 的物件 `item`，經過 `updateQuality` 呼叫之後，其 `quality` 不減反增，即使在 `sellIn` 歸零也是

實際撰寫測試的流程

- 新增測試 method `testAgedBrieUpdateQualityIsAlwaysIncrease`
- 初始化商品的 `sellIn` 為 0 ，然後 `quality` 為 2，這裡的名稱改為 `Aged Brie`
- 撰寫測試，預期在 `updateQuality` 之後， `quality` 為 3
- 執行測試，綠燈

```php

public function testAgedBrieUpdateQualityIsAlwaysIncrease()
{
    $this->createItem("Aged Brie", 0, 2);
    $this->updateQuality();
    $this->shouldBe(0, 3);
}
```

[git 測試案例四提交記錄](https://github.com/mpp21x/PHP-GildedRoseRefactoringKata/commit/9a9c984395adf1dd03c248133a9cdbbeab90f6c7)

### 測試案例五

- 需求： 確保 `item` 的 `quality` 從來不超過 50

這一個案例想補充一下，按理來說應該可以測試兩個地方：

- 初始化 `item` 的時候`quality` 就不允許 50 以上的參數
- 新增`quality` 的時候，到達 50 就不給予新增

由於初始化不允許 50 以上的參數處理有兩種，要嘛自動降為 50 ，要嘛拋出 Exception ，但這裏題目也沒特別提到，我選擇自己腦補拋出 Exception。

實際撰寫測試的流程：

- 新增一個 `InvalidItemQualityException` Exception 物件在 `src` 目錄
- 修改 `Item` 物件的 `__construct` method 如下

```php
function __construct($name, $sellIn, $quality)
{
    if($quality > 50){
        throw new InvalidItemQualityException();
    }
    $this->name = $name;
    $this->sellIn = $sellIn;
    $this->quality = $quality;
}
```

- 新增測試 method `testProductQualityNeverGreaterThen_51`
- 建立一個 `quality` 為 51 的 `Item`，並且預期拋出一個 `InvalidItemQualityException` 的 Exception
- 執行測試，綠燈
- 建立一個 `Aged Brie` 的物件， `quality` 為 50
- 撰寫測試，預期在 `updateQuality` 之後， `quality` 仍然為 50
- 執行測試，綠燈

```php

public function testProductQualityNeverGreaterThen_51()
{
    $this->expectException(InvalidItemQualityException::class);
    $this->createItem("foo", 0, 51);

    $this->createItem("Aged Brie", 0, 50);
    $this->updateQuality();
    $this->shouldBe(0, 50);
}
```

[git 測試案例五提交記錄](https://github.com/mpp21x/PHP-GildedRoseRefactoringKata/commit/76b9003a8befd6d9de922111d43a1bd0c9bb9537)

### 測試案例六

- 需求：魔法槌商品的物件 `item` ，其 `sellIn` 和 `quality` 初始化後，永久不變

實際撰寫測試的流程：

- 新增測試 method `testSulfurasSellInQuality`
- 建立一個名稱為 `Sulfuras, Hand of Ragnaros` 的 `Item`， `sellIn` 和 `quality` 為 3
- 撰寫測試，預期在 `updateQuality` 之後， `sellIn` 和 `quality` 仍然為 3
- 執行測試，綠燈

```php

public function testSulfurasSellInQuality()
{
    $this->createItem("Sulfuras, Hand of Ragnaros", 3, 3);
    $this->updateQuality();
    $this->shouldBe(3, 3);
}
```

[git 測試案例六提交記錄](https://github.com/mpp21x/PHP-GildedRoseRefactoringKata/commit/7d1b82d0632bf68471ef5f39c5bb5b20d94937b9)

### 測試案例七

- 需求：商品 Backstage passes 的物件 `item` 須滿足下列條件：
    - 當 `sellIn` 為 `9` 到 `6` 的時候，`updateQuality` 一次提升 `quality` 2
    - 當 `sellIn` 為 `5` 到 `1` 的時候，`updateQuality` 一次提升 `quality` 3
    - 當 `sellIn` 為 `0`，`quality` 將直接歸零

這邊標題雖然打測試案例七，但實際上是增加 3 個測試案例，皆是針對 `Backstage` 商品的。

實際撰寫測試的流程：
#### 測試案例七之一 ： 當 `sellIn` 為 `9` 到 `6` 的時候，`updateQuality` 一次提升 `quality` 2
- 新增測試 method `testBackstagePassesQualityWhenSellInIsLessThanTenDay`
- 建立一個名稱為 `Sulfuras, Hand of Ragnaros` 的 `Item`， `sellIn` 為 9 和 `quality` 為 0
- 測試在 `sellIn` 為 9 ~ 6 的時候，每次 `updateQuality` 都會新增 2
- 執行測試，綠燈

```php

public function testBackstagePassesQualityWhenSellInIsLessThanTenDay()
{
    $expectedSellIn = 10;
    $this->createItem("Backstage passes to a TAFKAL80ETC concert", $expectedSellIn, 0);
    $exceptedQuality = 2;

    $exceptedSellIns = range($expectedSellIn, 6);
    foreach ($exceptedSellIns as $expectedSellIn) {
        $this->updateQuality();
        $expectedSellIn--;
        $this->shouldBe($expectedSellIn, $exceptedQuality);
        $exceptedQuality += 2;
    }
}
```

[git 測試案例七之一提交記錄](https://github.com/mpp21x/PHP-GildedRoseRefactoringKata/commit/e777742aaf09f33fde73464791f9f0cbcbbfdef0)

#### 測試案例七之二 ： 當 `sellIn` 為 `5` 到 `1` 的時候，`updateQuality` 一次提升 `quality` 3

- 新增測試 method `testBackstagePassesQualityWhenSellInIsLessThanFiveDay`，可以直接複製上一個 測試  。
- 測試在 `sellIn` 為 5 ~ 1 的時候，每次 `updateQuality` 都會新增 3
- 執行測試，綠燈

```php

public function testBackstagePassesQualityWhenSellInIsLessThanFiveDay()
{
    $expectedSellIn = 5;
    $this->createItem("Backstage passes to a TAFKAL80ETC concert", $expectedSellIn, 0);
    $exceptedQuality = 3;

    $exceptedSellIns = range($expectedSellIn, 2);
    foreach ($exceptedSellIns as $expectedSellIn) {
        $this->updateQuality();
        $expectedSellIn--;
        $this->shouldBe($expectedSellIn, $exceptedQuality);
        $exceptedQuality += 3;
    }
}
```

[git 測試案例七之二提交記錄](https://github.com/mpp21x/PHP-GildedRoseRefactoringKata/commit/5d8fccec9f06dc51d6e5f87f704ab8cabd53aea2)

#### 測試案例七之三 ： 當 `sellIn` 為 0， `quality` 將直接歸零

- 新增測試 method `testBackstagePassesQualityIsZeroWhenSellInIsZero`，可以直接複製上一個 測試  。
- 測試初始化 `sellIn` 為 1 而 `quality` 為 30 的時候， `updateQuality` 之後，預期 `quality` 為 0

```php

public function testBackstagePassesQualityIsZeroWhenSellInIsZero()
{
    $this->createItem("Backstage passes to a TAFKAL80ETC concert", 1, 30);
    $this->updateQuality();
    $this->shouldBe(0, 0);
}
```


- 執行測試，紅燈： `Failed asserting that 33 is identical to 0.`
- 修改 `GildedRose` 物件，滿足測試案例

![](test_case_7.png)

- 執行測試，綠燈

[git 測試案例七之三提交記錄](https://github.com/mpp21x/PHP-GildedRoseRefactoringKata/commit/903edbc48f3f3f755b8387896d3a3d43d045a830)

## 重構 legacy code

### 重構波動拳程式碼的策略

我們已經完成了除了測試案例 9 的所有測試案例了，在測試的保護下我們即將對 legacy code 進行重構，也就是清除波動拳的程式碼 `updateQuality` 。

重構的目的是希望在保留既有的程式碼 **功能** 之下，優化程式碼的架構，我在處理波動拳的程式時(這題先不考慮以物件為單位分離職責的)，有自己常用的套路：

- 可以合併的 if / else 或是可 extract 成一個 method 程式便 extract，並給予一個更容易了解的命名方式
- 充分確認流程的前因後果後，以 method 的方式分離職責，並調整執行的順序。
- 扁平化 if / else 結構，在 clean code 裡面，盡量避免 if / else 裡面有過多的 if / else ，可使用 `return` & `switch` 來幫助我們完成這個任務
- 調整後不忘進行測試，確保調整是安全無慮的
- 專注測試一個測試案例，保持測試案例獨立性的同時，也保留舊有的 legacy code
    * 例如，將新增的 switch 放在舊有 legacy code 最前方，並專注一個 `case` 撰寫，執行完之後便 `return` ，逐步完成所有程式碼，最後即可移除 legacy code。  

其實本來想嘗試描述重構過程，但發現要描述整個流程過於繁瑣冗長，這裡就跳過了描述了，請直接看重構後的程式碼。

[git 重構後的提交記錄](https://github.com/mpp21x/PHP-GildedRoseRefactoringKata/commit/dd9287b1f8666f8916825c821b8ef3783b40b723)


## TDD 新增新的需求

需求：新增一個 Conjured 商品，`updateQuality` 所下降的值為正常商品的兩倍

實際撰寫測試的流程：

- TDD 的開發流程為 **紅燈** → **綠燈** → **重構**，所以我們先按照需求寫測試，取得第一個紅燈
- 新增一個測試  method `testConjuredUpdateQuality`

```php

public function testConjuredUpdateQuality()
{
    $this->createItem("Conjured Mana Cake", 2, 20);
    $this->updateItems();
    $this->shouldBe(1, 18);
}

```

- 測試，紅燈： `Failed asserting that 2 is identical to 1.`
- 再來修改 `GildedRose` 物件的 `updateItem` method 來滿足需求，首先修改 `updateItem` Method

```php

protected function updateItem(Item $item): void
{
    switch ($item->name) {
        // ...省略
        case "Conjured Mana Cake":
            $this->updateConjured($item);
            break;
        default:
            $this->updateNoramlProduct($item);
    }
}

```

- 再來處理細節 `updateConjured` method

```php
protected function updateConjured(Item $item): void
{
    if ($item->sellIn > 0) {
        $item->sellIn--;
    }

    $decreaseNum = 2;
    if (($item->quality - $decreaseNum) < 0) {
        $item->quality = 0;
        return;
    }
    $item->quality = $item->quality - $decreaseNum;
}
```

- 執行測試，綠燈
- 目前為止，所有的測試案例便算是完成了，在 TDD 的流程，本該是綠燈後進行重構，但程式碼看下來沒有發現需要重構的部分，所以便在此結束

[git TDD新增的提交記錄](https://github.com/mpp21x/PHP-GildedRoseRefactoringKata/commit/ca2c117051e3bf497b18d50d39987eacd473dc2e)


## 修正測試案例五漏洞
* 需求： 確保商品劇院後台通行證(Backstage passes) `item` 的 `quality` 從來不超過 50

剛剛在整理整個筆記的時候，意外發現 測試案例五 有一個漏網之魚便是商品劇院後台通行證(Backstage passes)，所以這裡再新增一個測試案例來補足這一部分。

實際撰寫測試的流程：

* 新增測試 method `testBackstagePassesQualityNeverGreaterThen_51`
* 依照 劇院後台通行證 不同的 3 種情況，新增 quality 數值，來撰寫測試，預期皆不超過 50

```php
public function testBackstagePassesQualityNeverGreaterThen_51()
{
    $this->createItem("Backstage passes to a TAFKAL80ETC concert", 15, 50);
    $this->updateItems();
    $this->shouldBe(14, 50);

    $this->createItem("Backstage passes to a TAFKAL80ETC concert", 9, 49);
    $this->updateItems();
    $this->shouldBe(8, 50);

    $this->createItem("Backstage passes to a TAFKAL80ETC concert", 4, 49);
    $this->updateItems();
    $this->shouldBe(3, 50);
}
```
* 執行測試，紅燈：`Failed asserting that 51 is identical to 50.`
* 基本上寫完第一個是紅燈，再來我們調整物件 `GildedRose`
* 這邊調整的部分較多，首先先在 `updateItems` 的 method 新增一個新的 method

```php
public function updateItems()
{
    foreach ($this->items as $item) {
        $this->updateItem($item);
        $this->setItmQualityWhenTooMuch($item);
    }
}

// ... 省略

protected function setItmQualityWhenTooMuch(Item $item)
{
    if($item->quality > 50){
        $item->quality = 50;
    }
}
```

* 這樣的設計是傾向，無論 `quality` 怎麼加，只要超過 50 便重設至 50，這樣寫的好處是可以一次適用所有的產品，而且考量到原本的設計還會先用 `if` 檢查，兩種寫法相較之下，無疑前者的寫法更為簡潔有力。
* 所以這裏我們不忘移除掉在 `updateAgedBrieQuality` 裡面不必要的檢查

![](update_check.png)

* 執行測試，綠燈

[git 修正測試案例五漏洞 的提交](https://github.com/mpp21x/PHP-GildedRoseRefactoringKata/commits/master)

##### 其他參考連結

這裏順便附上讀書會其他人分享的解題思路。

* [講者 Blues 的解題思路](https://github.com/holyblue/GildedRoseKata)

* [主辦社群成員 Fong 的解題](https://gist.github.com/FongX777/fb36c10fb698e69adbf027044cfc88ec)
