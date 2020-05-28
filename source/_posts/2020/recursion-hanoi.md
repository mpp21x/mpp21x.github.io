---
title: hanoi recursive algorithm
date: 2020-05-18 21:52:01
tags:
- javascript
- algorithm
img: /medias/page_images/2020/Tower_of_Hanoi.jpeg
categories: algorithm
---

Tower of Hanoi ，非常知名的益智遊戲，同時也是很好的一個訓練遞迴思維的演算法，本篇記錄自己學習河內塔的過程。

## 題目印出河內塔運算的移動軌跡

想像在桌面上有三個柱子，最左邊的柱子掛了三個圓圈，每個圓圈不一，越下面的圓圈半徑越大，現在我們要試者把所有的圓圈移動至最右邊的柱子，移動時有一個規則如下：

* 每一個柱子擺放圓圈時，必須從大至小，由下往上擺放，簡單來說：越大的圓圈放越下面。

現在嘗試用 JavaScript 來撰寫一個 function，使其 print 出 移動的軌跡。

```javascript
function hanoiStart(total, from, temp, to) {

}

console.log('first round ------');
/**
 * A -> C
 */
hanoiStart(1, 'A', 'B' ,'C');


console.log('second round ------');
/**
 * A -> B
 * A -> C
 * B -> C
 */
hanoiStart(2, 'A', 'B' ,'C');


console.log('third round ------');
/**
 * A -> C
 * A -> B
 * C -> B
 * A -> C
 * B -> A
 * B -> C
 * A -> C
 */
hanoiStart(3, 'A', 'B', 'C');
```

`hanoiStart` 的參數意義如下：
* `total`：最左邊的圓圈數，若為 1，則代表最左邊的柱子只有一個圓圈
* `from`：欲移動的柱子名稱
* `temp`：此次不參與移動的柱子名稱
* `to`：欲移動目標位置的柱子名稱

上面的註解代表此題預期的答案，以下面參數為例。

```javascript
hanoiStart(1, 'A', 'B' ,'C');
```

答案則應為

```javascript
/**
 * A -> C
 */
```

現在我們要做的便是使下列這三個 function print 出來的軌跡與上方註解的答案一樣。

```javascript
hanoiStart(1, 'A', 'B', 'C');
hanoiStart(2, 'A', 'B', 'C');
hanoiStart(3, 'A', 'B', 'C');
```

## 解題過程

### 一個圓圈
`hanoiStart(1, 'A', 'B' ,'C')`，當只有一個圓圈時，便是直接移動即可。

```javascript
function hanoiStart(total, from, temp, to) {
    const move = (from, to) => console.log(`${from} -> ${to}`);
    move(from, to);
}
```

### 兩個圓圈
`hanoiStart(2, 'A', 'B' ,'C')`，當圓圈有兩個的時候，我們知道其移動軌跡如下：

```javascript
/**
 * A -> B
 * A -> C
 * B -> C
 */
```

如果是首次練習的人，建議可以準備紙筆，自己運算一下移動過程。

這個過程我們可以觀察出兩件事情。

#### 1. 執行次數

* `total` 為 1 時，移動 1 次
* `total` 為 2 時，移動 3 次

這裡可以得出每一次移動次數與參數 `total` 的公式為 `2ⁿ-1`，`n` 代表參數 `total`。

`2ⁿ-1` 同時也代表者 `hanoiStart` 被呼叫的次數，此時回到我們的函式。

#### 2. 由下而上的逐步移動

一開始的移動，必然是以最下面的圓圈為思考出發點，然後首先要思考的是，如何讓此圓圈可以移到目標？

便是將上面的圓圈，移開，倘若今天最下面的圓圈上方有兩個圓圈，也是同樣道理。

為了使最下方的圓圈可以移動，上面的圓圈也要先移動，等到移動完了才能動最下方的圓圈，這也可以用遞迴的思維來思考。

思考過上面兩個點之後，開始撰寫程式碼，開頭一樣先用較直觀的方式寫。

```javascript
function hanoiStart(total, from, temp, to) {
    const move = (from, to) => console.log(`${from} -> ${to}`);
    if (total === 1) {
        return move(from, to);
    }
    move(from, temp);
}
```

此時畫面顯示的是：

```
A -> B
```

再來我們便會需要思考如何進行下去，使用方式便是剛剛想到的遞迴，那麼同時把 `move(from, temp)` 也改成遞迴。

```javascript
function hanoiStart(total, from, temp, to) {
    const move = (from, to) => console.log(`${from} -> ${to}`);
    if (total === 1) {
        return move(from, to);
    }
    hanoiStart(total - 1, from, to, temp);
}
```

此時的河內塔運算，已經滿足了移開最下面的圓圈以上的所有圓圈，此時要做的便是將此圓圈移動到目標柱子。

```javascript
function hanoiStart(total, from, temp, to) {
    const move = (from, to) => console.log(`${from} -> ${to}`);
    if (total === 1) {
        return move(from, to);
    }
    hanoiStart(total - 1, from, to, temp);
    move(from, to);
}
```

走到這步後，只剩下再將最一開始 `temp` 的圓圈移到 `to` 即可。

```javascript
function hanoiStart(total, from, temp, to) {

    const move = (from, to) => console.log(`${from} -> ${to}`);
    if (total == 1) {
        return move(from, to);
    }
    hanoiStart(total - 1, from, to, temp);
    move(from, to);
    hanoiStart(total - 1, temp, from, to)
}
```

目前為止就算完成了，此時在看發現，這樣運算的次數也剛好符合前面所總結到的 `2ⁿ-1`。



### 三個圓圈

已完成，測試成功，事實上這也適用於四個圓圈數，邏輯相同。

## 簡單總結：

基本上，每一次的一個圓圈移動到目標柱子都是兩個步驟：
* 先移動除了最下方圓圈以外的所有圓圈。
* 移動最下方的圓圈。

只要掌握這個原則，再以原則套用在移動在一開始最左邊的所有柱子，基本上就可以得出移動的軌跡了。

不過這也僅適用於此文章描述的情境，事實上河內塔還有更多複雜的變化。

## 相關連結

* [How to solve the 'Tower of Hanoi' puzzle (with 4 discs)](https://www.youtube.com/watch?v=5Wn4EboLrMM)

* [［離散數學］河內塔難題再下一塔](https://sites.google.com/a/g2.nctu.edu.tw/unimath/2017-11/Hanoi)
* [練習連結](https://github.com/mpp21x/algorithm-exercise/tree/master/hanoi)
