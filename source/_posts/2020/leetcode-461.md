---
title: LeetCode 461. Hamming Distance
date: 2020-05-14 01:17:20
tags:
- leetcode
- javascript
- algorithm
- java
img: /medias/page_images/2020/leetcode-461-js.png
categories: leetcode algorithms
---
* link：[461. Hamming Distance](https://leetcode.com/problems/hamming-distance/submissions/)
* difficulty：`easy`
* language：`javascript`

## 題目原文

The Hamming distance between two integers is the number of positions at which the corresponding bits are different.

Given two integers x and y, calculate the Hamming distance.

Note:

0 ≤ `x`, `y` < 2³¹.

Example：

```
Input: x = 1, y = 4

Output: 2

Explanation:
1   (0 0 0 1)
4   (0 1 0 0)
       ↑   ↑

The above arrows point to positions where the corresponding bits are different.
```

## 解題思路

我們必須將兩個變數轉為二進位，並且取得其不同值的位置。

* 將兩個變數，從十進位轉二進位，而轉十進位至二進位，如果是透過人工計算，便是將整數一直除以 2 直到商數為 0，然後將最後除得商數，再加上每一次的餘數反轉排序後，即可得到二進位：個人記十進位轉二進位的口訣為： **最後商加反轉全餘數**。 
* 再來統計不同的位數，即可得出答案。


## 實作 JavaScript

這是我最一開始用的方法，將 `x`, `y` 轉二進位，然後取得位數差，在每次遍歷時檢查順便遞補。

```javascript
var hammingDistance = function (x, y) {
    var big = Math.max(x, y);
    var small = Math.min(x, y);
    big = (big).toString(2).split('');
    small = (small).toString(2).split('');
    var bigLength = big.length;
    var currentDifferentNum = bigLength - small.length;
    var distance = 0;
    for (var i = 0; i < bigLength; i++) {
        var isNotSameLength = currentDifferentNum !== 0;
        if (isNotSameLength) {
            currentDifferentNum--;
            small.unshift('q');
            if (big[i] !== '0') {
                distance++;
            }
            continue;
        }
        distance += big[i] !== small[i] ? 1 : 0;
    }
    return distance;
};
```

這是後來看到別人使用 bitwise 的一行寫法（汗顏）


```javascript
var hammingDistance = function(x, y) {
    return (x ^ y).toString(2).replace(/0/g, '').length;
};

console.log(hammingDistance(1, 4)); // should be 2
console.log(hammingDistance(3, 1)); // should be 1
console.log(hammingDistance(93, 73)); // should be 2
```

![The result](one.png)


`x ^ y`：`^` is Javascript bitwise operator,It can get a number which is binary to deciaml(if not same then add `1`) , can help us to identity the different positions.

* `15 & 9` result is `9` (`1111 & 1001 = 1001`)
* `15 | 9` result is `15` (`1111 | 1001 = 1111`)
* `15 ^ 9` result is `6` (`1111 ^ 1001 = 0110`)


`toString(2)`：decimal to number.

`replace(/0/g, '')`：to replace all the same position.

## 實作 Java

```java
public class Solution {
    public int hammingDistance(int x, int y) {
        return Integer.bitCount(x ^ y);
    }
} 
```

```java
public class HelloWorld {
    public static void main(String[] args) {
        Solution s = new Solution();
        System.out.println(s.hammingDistance(1, 4));//
    }
}
```


### GitHub 

[This is my code of practice link.](https://github.com/mpp21x/algorithm-exercise/tree/master/461.hammingDistance)

