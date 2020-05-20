---
title: LeetCode(771) - Jewels and Stones (js/easy)
date: 2020-05-21 01:40:42
tags: 
- leetcode
- javascript
- algorithm
img: /medias/page_images/leetcode_771_js.png
---
* link：[771. Jewels and Stones](https://leetcode.com/problems/jewels-and-stones/)
* difficulty：`easy`
* language：`javascript`

## Question


You're given strings `J` representing the types of stones that are jewels, and `S` representing the stones you have.  Each character in `S` is a type of stone you have.  You want to know how many of the stones you have are also jewels.

The letters in `J` are guaranteed distinct, and all characters in `J` and `S` are letters. Letters are case sensitive, so "a" is considered a different type of stone from "A".

Example 1:

```
Input: J = "aA", S = "aAAbbbb"
Output: 3
```

Example 2:

```
Input: J = "z", S = "ZZ"
Output: 0
```

Note:

* `S` and `J` will consist of letters and have length at most 50.
* The characters in `J` are distinct.


### Question Explanation

參數 `J` 的每一個字元代表一種珠寶，我們要的做的便是找出參數 `S` 當中，有多少珠寶的石頭。


Each character in `J`  representing that is jewel, And we need to use these characters to count how many jewels in the strings `S`. 

## How to implement algorithm in javascript

基本上這題解題的思路並不困難，主要是由遍歷這兩個字串來找出答案，只是如果想要速度更快的話，則必須熟悉 JavaScript 裡面一些物件如 `map` 或是 `set` 。

### 第一種方式 array
```javascript
var numJewelsInStones = function (J, S) {
    let count = 0;
    for (let stone of S) {
        count += J.indexOf(stone) !== -1 ? 1 : 0;
    }
    return count;
}
```

這裡的 `indexOf` 也可以改成 `includes`

最簡單的版本，然而速度並不快，其速度如下。

![第一種方式 array](first.png)

### 第二種方式 Set

再來我們試試看另一種方法，便是 ES6 `Set`，之所以用 `Set` 是因為兩個原因：
* 允許使用字串作為參數
* unique value

```typescript
var numJewelsInStones = function (J, S) {
    // @ts-ignore
    J = new Set(J);
    let count =0;
    for (let stone of S) {
        count += J.has(stone)  ? 1 : 0;
    }
    return count;
};
```

![第二種方式 Set](second.png)

### 第三種方式 Set & filter

速度似乎有比較快了，但是 memory 變高了一些，再來我們試試看將 `S` 轉換成陣列操作好了，使用 `split` 搭配 `filter`，可省略 `count` 變數。

```typescript
var numJewelsInStones = function (J, S) {
    // @ts-ignore
    J = new Set(J);
    return S.split('').filter((stone) => J.has(stone)).length;
};
```

![第三種方式 Set & filter](third.png)

這樣速度反而變更慢了。

### 第四種方式 Set & filter 搭配 destructuring

試試看用 destructuring operator 使 `S` 轉換成陣列。

```typescript
var numJewelsInStones = function (J, S) {
    // @ts-ignore
    J = new Set(J);
    return [...S].filter((stone) => J.has(stone)).length;
};
```

這次測試的變化幅度很大，最慢可以到 100ms ，最快可以到 40 ms，似乎不是很穩，這裡就取一個速度較快的作為封面了。

![第四種方式 Set & filter 搭配 destructuring](fourth.png)

## 其他

### GitHub 紀錄

此次練習被收錄在我的 GitHub [點我連結](https://github.com/mpp21x/algorithm-exercise/tree/master/771.jewelsAndStones)。 
